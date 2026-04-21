<?php

namespace App\Applications\Http\Api\CourseRegistration\Controllers;

use App\Domains\Course\Enums\CourseStudentStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseRegistrationController
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'external_id'             => ['required', 'string', 'regex:/^[cw]\d+$/'],
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

        $externalId = $validated['external_id'];
        $prefix = $externalId[0];
        $id = (int) substr($externalId, 1);

        Log::info('[WP Registration] Incoming registration', [
            'external_id' => $externalId,
            'email'       => $validated['email'],
        ]);

        $student = $this->findOrCreateStudent($validated);

        if ($prefix === 'c') {
            return $this->registerForCourse($id, $student, $validated);
        }

        return $this->registerForWebinar($id, $student, $validated);
    }

    private function registerForCourse(int $courseId, Student $student, array $data): JsonResponse
    {
        $course = Course::find($courseId);

        if (! $course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        if ($student->courses()->where('course_id', $course->id)->exists()) {
            return response()->json([
                'success'         => true,
                'status'          => 'already_registered',
                'registration_id' => $student->id,
            ]);
        }

        $metadata = $this->buildMetadata($data);

        $status = $course->price > 0
            ? CourseStudentStatus::Inactive
            : CourseStudentStatus::Active;

        $student->courses()->attach($course->id, [
            'status'      => $status->value,
            'enrolled_at' => now(),
            'notes'       => json_encode($metadata, JSON_UNESCAPED_UNICODE),
        ]);

        return response()->json([
            'success'         => true,
            'status'          => 'registered',
            'registration_id' => $student->id,
        ]);
    }

    private function registerForWebinar(int $webinarId, Student $student, array $data): JsonResponse
    {
        $webinar = Webinar::find($webinarId);

        if (! $webinar) {
            return response()->json(['error' => 'Webinar not found'], 404);
        }

        if ($webinar->students()->where('student_id', $student->id)->whereNull('webinar_student.cancelled_at')->exists()) {
            return response()->json([
                'success'         => true,
                'status'          => 'already_registered',
                'registration_id' => $student->id,
            ]);
        }

        $webinar->students()->attach($student->id, [
            'registered_at' => now(),
        ]);

        return response()->json([
            'success'         => true,
            'status'          => 'registered',
            'registration_id' => $student->id,
        ]);
    }

    private function buildMetadata(array $data): array
    {
        return array_filter([
            'full_name'               => $data['full_name'] ?? null,
            'education_level'         => $data['education_level'] ?? null,
            'university'              => $data['university'] ?? null,
            'faculty'                 => $data['faculty'] ?? null,
            'graduation_year'         => $data['graduation_year'] ?? null,
            'speciality'              => $data['speciality'] ?? null,
            'additional_specialities' => $data['additional_specialities'] ?? null,
            'diploma_series'          => $data['diploma_series'] ?? null,
            'diploma_number'          => $data['diploma_number'] ?? null,
            'workplace'               => $data['workplace'] ?? null,
            'position'                => $data['position'] ?? null,
            'source'                  => 'wp_registration',
        ], fn ($v) => $v !== null);
    }

    private function findOrCreateStudent(array $data): Student
    {
        $student = Student::where('email', $data['email'])->first();

        if ($student) {
            return $student;
        }

        $phone = $this->normalizePhone($data['phone'] ?? null);

        return Student::create([
            'number'   => Student::generateNumber(),
            'email'    => $data['email'],
            'name'     => $data['first_name'],
            'surname'  => $data['last_name'],
            'phone'    => $phone,
            'birthday' => $data['birth_date'] ?? null,
            'city'     => $data['city'] ?? null,
        ]);
    }

    private function normalizePhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) === 12 && str_starts_with($digits, '380')) {
            $normalized = '+' . $digits;
            if (preg_match('/^\+380\d{9}$/', $normalized)) {
                return $normalized;
            }
        }

        return null;
    }
}
