<?php

namespace Database\Seeders;

use App\Models\JobPost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobPost::factory()->count(50)->create();
    }
}
