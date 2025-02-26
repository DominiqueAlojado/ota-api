<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;

class JobPostController extends Controller
{
    public function approve($id)
    {
        $job = JobPost::findOrFail($id);
        $job->status = 'published';
        $job->touch();
        $job->save();

        return view('job.success', ['message' => 'Job post has been approved!']);
    }

    public function markAsSpam($id)
    {
        $job = JobPost::findOrFail($id);
        $job->status = 'unpublished';
        $job->is_spam = '1';
        $job->touch();
        $job->save();

        return view('job.success', ['message' => 'Job post has been marked as spam.']);
    }
}
