<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailNotificationJob;
use App\Models\JobPost;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class JobPostController extends Controller
{

    public function index(): JsonResponse
    {
        try {
            $jobs = JobPost::where('status', 'published')
                ->where(function ($query) {
                    $query->whereNull('is_spam')
                        ->orWhere('is_spam', '<>', 1);
                })
                ->orderBy('updated_at', 'desc')
                ->get();
            $response = Http::get('https://mrge-group-gmbh.jobs.personio.de/xml');

            if ($response->failed()) throw new Exception('Failed to fetch external jobs.');

            // Convert XML to JSON
            $xmlData = new SimpleXMLElement($response->body());
            $externalJobs = [];

            foreach ($xmlData->position as $position) {
                $jobDescriptions = [];
                foreach ($position->jobDescriptions->jobDescription as $jobDesc) {
                    $jobDescriptions[] = [
                        'title' => (string) $jobDesc->name,
                        'description' => strip_tags((string) $jobDesc->value)
                    ];
                }

                $externalJobs[] = [
                    'job_title' => (string) $position->name,
                    'company_name' => (string) $position->subcompany,
                    'job_description' => implode("\n", array_map(fn($desc) => $desc['title'] . ': ' . $desc['description'], $jobDescriptions)),
                    'company_address' => (string) $position->office,
                    'job_type' => (string) $position->employmentType,
                    'seniority_level' => (string) $position->seniority,
                    'work_schedule' => (string) $position->schedule,
                    'experience_range' => (string) $position->yearsOfExperience,
                    'keywords' => (string) $position->keywords,
                    'posted_at' => Carbon::parse($position->createdAt)->diffForHumans(),
                ];
            }

            $allJobs = $jobs->toArray();
            $mergedJobs = array_merge($allJobs, $externalJobs);

            return response()->json([
                'message' => 'Job posts retrieved successfully!',
                'jobs' => $mergedJobs,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database error!',
                'message' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'job_title' => 'required|string|max:255',
                'job_description' => 'required|string',
                'company_name' => 'required|string|max:255',
                'company_address' => 'required|string|max:255',
                'company_email_address' => 'required|email',
                'job_type' => ['required'],
                'seniority_level' => ['required'],
                'work_schedule' => ['required'],
                'experience_range' => ['required'],
                'keywords' => 'nullable|string',
                'status' => ['nullable']
            ]);
            # Get the latest job post
            $latestJob = JobPost::where('company_email_address', $validatedData['company_email_address'])
                ->latest('created_at')
                ->first();
            # Consider first-time if no job exists or last was spam
            $isFirstTimePoster = !$latestJob || $latestJob->is_spam == 1;

            #If not a first-time poster and the latest job is NOT spam, auto-publish
            $validatedData['status'] = (!$isFirstTimePoster) ? 'published' : ($validatedData['status'] ?? 'unpublished');

            $job = JobPost::create($validatedData);
            if ($isFirstTimePoster) $this->firstTimeJobPosterChecker($job);

            return response()->json([
                'message' => 'Job post created successfully!',
                'job' => $job,
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database error!',
                'message' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function firstTimeJobPosterChecker(object $dataset): void
    {
        $moderatorEmail = env('MODERATOR_EMAIL');
        try {
            SendEmailNotificationJob::dispatch($dataset, $moderatorEmail)
                ->onQueue('emails')
                ->delay(now()->addSeconds(10));
        } catch (\Exception $e) {
            Log::error("Failed to queue job post notification: " . $e->getMessage());
        }
    }
}
