<?php

namespace App\Applications\Http\Admin\Progress\Controllers;

use App\Domains\Course\Models\Course;
use Illuminate\Http\JsonResponse;

class GetStudentsByCourseController
{
    public function __invoke(Course $course): JsonResponse
    {
        $students = $course->students()
            ->orderBy('surname')
            ->orderBy('name')
            ->get(['students.id', 'name', 'surname', 'email'])
            ->map(fn ($student) => [
                'id' => $student->id,
                'name' => "{$student->surname} {$student->name}",
                'email' => (string) $student->email,
            ]);

        return response()->json($students);
    }
}
