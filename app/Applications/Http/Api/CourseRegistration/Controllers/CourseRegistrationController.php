<?php

namespace App\Applications\Http\Api\CourseRegistration\Controllers;

use App\Domains\Course\Enums\CourseStudentStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseRegistrationController
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id'               => ['required', 'integer'],
            'email'                   => ['required', 'email'],
            'first_name'              => ['required', 'string'],
            'last_name'               => ['required', 'string'],
            'full_name'               => ['nullable', 'string'],
            'phone'                   => ['nullable', 'string'],
            'birth_date'              => ['nullable', 'date_format:Y-m-d'],
            'education_level'         => ['nullable', 'string'],
            'university'              => ['nullable', 'string'],
            'faculty'                 => ['nullable', 'string'],
            'graduation_year'         => ['nullable', 'integer'],
            'speciality'              => ['nullable', 'string'],
            'additional_specialities' => ['nullable', 'string'],
            'diploma_series'          => ['nullable', 'string'],
            'diploma_number'          => ['nullable', 'string'],
            'city'                    => ['nullable', 'string'],
            'workplace'               => ['nullable', 'string'],
            'position'                => ['nullable', 'string'],
        ]);

        Log::info('[WP Registration] Incoming registration', [
            'course_id' => $validated['course_id'],
            'email'     => $validated['email'],
        ]);

        $course = Course::find($validated['course_id']);

        if (! $course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $student = $this->findOrCreateStudent($validated);

        if ($student->courses()->where('course_id', $course->id)->exists()) {
            return response()->json([
                'success'         => true,
                'status'          => 'already_registered',
                'registration_id' => $student->id,
            ]);
        }

        $metadata = array_filter([
            'full_name'               => $validated['full_name'] ?? null,
            'education_level'         => $validated['education_level'] ?? null,
            'university'              => $validated['university'] ?? null,
            'faculty'                 => $validated['faculty'] ?? null,
            'graduation_year'         => $validated['graduation_year'] ?? null,
            'speciality'              => $validated['speciality'] ?? null,
            'additional_specialities' => $validated['additional_specialities'] ?? null,
            'diploma_series'          => $validated['diploma_series'] ?? null,
            'diploma_number'          => $validated['diploma_number'] ?? null,
            'workplace'               => $validated['workplace'] ?? null,
            'position'                => $validated['position'] ?? null,
            'source'                  => 'wp_registration',
        ], fn ($v) => $v !== null);

        $student->courses()->attach($course->id, [
            'status'      => CourseStudentStatus::Active->value,
            'enrolled_at' => now(),
            'notes'       => json_encode($metadata, JSON_UNESCAPED_UNICODE),
        ]);

        return response()->json([
            'success'         => true,
            'status'          => 'registered',
            'registration_id' => $student->id,
        ]);
    }

    private function findOrCreateStudent(array $data): Student
    {
        $email = $data['email'];

        $student = Student::where('email', $email)->first();

        if ($student) {
            return $student;
        }

        $phone = $this->normalizePhone($data['phone'] ?? null);

        return Student::create([
            'number'   => Student::generateNumber(),
            'email'    => $email,
            'name'     => $data['first_name'],
            'surname'  => $data['last_name'],
            'phone'    => $phone,
            'birthday' => $data['birth_date'] ?? null,
            'city'     => $data['city'] ?? null,
        ]);
    }

    /**
     * Normalize a phone string to +380XXXXXXXXX format, or return null if not possible.
     */
    private function normalizePhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        // Handle leading "380" without "+"
        if (strlen($digits) === 12 && str_starts_with($digits, '380')) {
            $normalized = '+' . $digits;
            if (preg_match('/^\+380\d{9}$/', $normalized)) {
                return $normalized;
            }
        }

        return null;
    }
}
