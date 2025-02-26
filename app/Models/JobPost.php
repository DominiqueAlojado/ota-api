<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;
    protected $table = 'job_posts';

    protected $fillable = [
        'job_title',
        'job_description',
        'company_name',
        'company_address',
        'job_type',
        'seniority_level',
        'work_schedule',
        'experience_range',
        'keywords',
        'status',
        'is_spam',
        'company_email_address'
    ];

    protected $appends = ['posted_at'];

    public function getPostedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }
}
