<?php

use App\Http\Controllers\Api\JobPostController;
use Illuminate\Support\Facades\Route;

Route::controller(JobPostController::class)->group(function () {
    Route::get('/job-posting', 'index');
    Route::post('/job-posting', 'store');
});
