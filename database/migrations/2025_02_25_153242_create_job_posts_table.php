<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->text('job_description');
            $table->string('company_name');
            $table->string('company_address');
            $table->string('company_email_address');
            $table->string('job_type');
            $table->string('seniority_level');
            $table->string('work_schedule');
            $table->string('experience_range');
            $table->text('keywords');
            $table->string('status')->default('unpublished');
            $table->boolean('is_spam')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
