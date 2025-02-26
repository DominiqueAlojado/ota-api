<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPost>
 */
class JobPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_title' => fake()->jobTitle(),
            'job_description' => fake()->paragraph(8),
            'company_name' => fake()->company(),
            'company_address' => fake()->address(),
            'company_email_address' => fake()->unique()->companyEmail(),
            'job_type' => fake()->randomElement(['Full-time', 'Part-time', 'Contract', 'Internship']),
            'seniority_level' => fake()->randomElement(['Junior', 'Mid', 'Senior', 'Lead']),
            'work_schedule' => fake()->randomElement(['Day Shift', 'Night Shift', 'Flexible', 'Remote']),
            'experience_range' => fake()->randomElement(['0-1 years', '2-5 years', '5+ years']),
            'keywords' => implode(', ', fake()->words(5)), // Generates random keywords
            'status' => fake()->randomElement(['unpublished', 'published']),
            'is_spam' => fake()->boolean(5), // 5% chance of being marked as spam
        ];
    }
}
