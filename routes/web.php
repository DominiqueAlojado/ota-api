<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobPostController;

Route::get('/job-posting/approve/{id}', [JobPostController::class, 'approve'])->name('job.approve');
Route::get('/job-posting/spam/{id}', [JobPostController::class, 'markAsSpam'])->name('job.spam');
