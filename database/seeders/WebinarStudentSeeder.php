<?php

namespace Database\Seeders;

use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebinarStudentSeeder extends Seeder
{
    public function run(): void
    {
        $testStudent = Student::where('email', 'an.zhovna@gmail.com')->first();

        if (!$testStudent) {
            $this->command->warn('Test student an.zhovna@gmail.com not found. Skipping WebinarStudentSeeder.');

            return;
        }

        $webinars = Webinar::all();

        if ($webinars->isEmpty()) {
            $this->command->warn('No webinars found. Skipping WebinarStudentSeeder.');

            return;
        }

        $registrations = [];

        $upcomingWebinar = $webinars->firstWhere('status', WebinarStatus::Upcoming);
        if ($upcomingWebinar) {
            $registrations[] = [
                'webinar_id' => $upcomingWebinar->id,
                'student_id' => $testStudent->id,
                'registered_at' => now()->subDays(5),
                'attended_at' => null,
                'cancelled_at' => null,
                'transaction_id' => null,
            ];
        }

        $liveWebinar = $webinars->firstWhere('status', WebinarStatus::Live);
        if ($liveWebinar) {
            $registrations[] = [
                'webinar_id' => $liveWebinar->id,
                'student_id' => $testStudent->id,
                'registered_at' => now()->subDays(3),
                'attended_at' => null,
                'cancelled_at' => null,
                'transaction_id' => null,
            ];
        }

        $endedWebinar = $webinars->firstWhere('status', WebinarStatus::Ended);
        if ($endedWebinar) {
            $registrations[] = [
                'webinar_id' => $endedWebinar->id,
                'student_id' => $testStudent->id,
                'registered_at' => now()->subDays(20),
                'attended_at' => now()->subDays(14),
                'cancelled_at' => null,
                'transaction_id' => null,
            ];
        }

        $cancelledWebinar = $webinars->firstWhere('status', WebinarStatus::Cancelled);
        if ($cancelledWebinar) {
            $registrations[] = [
                'webinar_id' => $cancelledWebinar->id,
                'student_id' => $testStudent->id,
                'registered_at' => now()->subDays(10),
                'attended_at' => null,
                'cancelled_at' => now()->subDays(8),
                'transaction_id' => null,
            ];
        }

        $draftWebinar = $webinars->firstWhere('status', WebinarStatus::Draft);
        if ($draftWebinar) {
            $registrations[] = [
                'webinar_id' => $draftWebinar->id,
                'student_id' => $testStudent->id,
                'registered_at' => now()->subDays(2),
                'attended_at' => null,
                'cancelled_at' => null,
                'transaction_id' => null,
            ];
        }

        $recordedWebinar = $webinars->firstWhere('status', WebinarStatus::Recorded);
        if ($recordedWebinar) {
            $registrations[] = [
                'webinar_id' => $recordedWebinar->id,
                'student_id' => $testStudent->id,
                'registered_at' => now()->subDays(35),
                'attended_at' => null,
                'cancelled_at' => null,
                'transaction_id' => null,
            ];
        }

        foreach ($registrations as $registration) {
            DB::table('webinar_student')->insert($registration);
        }

        $this->command->info('Registered an.zhovna@gmail.com for '.count($registrations).' webinars (all statuses)');
    }
}
