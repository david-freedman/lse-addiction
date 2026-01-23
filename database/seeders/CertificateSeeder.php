<?php

namespace Database\Seeders;

use App\Domains\Certificate\Models\Certificate;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentCourseProgress;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $completedProgress = StudentCourseProgress::where('status', ProgressStatus::Completed)
            ->with(['student', 'course'])
            ->get();

        $count = 0;
        $created = [];

        foreach ($completedProgress as $progress) {
            $key = $progress->student_id.'-'.$progress->course_id;
            if (isset($created[$key])) {
                continue;
            }

            Certificate::factory()
                ->forStudent($progress->student)
                ->forCourse($progress->course)
                ->create([
                    'study_hours' => $progress->course->total_duration ?? 120,
                    'issued_at' => $progress->completed_at,
                ]);
            $created[$key] = true;
            $count++;
        }

        $this->command->info("Created {$count} certificates");
    }
}
