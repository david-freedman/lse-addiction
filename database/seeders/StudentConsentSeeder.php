<?php

namespace Database\Seeders;

use App\Domains\Student\Enums\ConsentType;
use App\Domains\Student\Models\Student;
use App\Domains\Student\Models\StudentConsent;
use Illuminate\Database\Seeder;

class StudentConsentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $consentTypes = ConsentType::cases();
        $createdCount = 0;

        foreach ($students as $student) {
            foreach ($consentTypes as $consentType) {
                StudentConsent::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'consent_type' => $consentType,
                    ],
                    [
                        'ip_address' => '127.0.0.1',
                        'document_version' => '1.0',
                        'consented_at' => $student->created_at,
                    ]
                );
                $createdCount++;
            }
        }

        $this->command->info("Created {$createdCount} student consents for ".count($students).' students');
    }
}
