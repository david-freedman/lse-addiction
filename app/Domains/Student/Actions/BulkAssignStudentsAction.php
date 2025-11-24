<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Data\BulkAssignData;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Facades\DB;

class BulkAssignStudentsAction
{
    public static function execute(BulkAssignData $data): int
    {
        $assignedCount = 0;

        DB::transaction(function () use ($data, &$assignedCount) {
            $students = Student::whereIn('id', $data->student_ids)->get();

            foreach ($students as $student) {
                if ($student->courses()->where('course_id', $data->course_id)->exists()) {
                    continue;
                }

                $student->courses()->attach($data->course_id, [
                    'status' => \App\Domains\Course\Enums\CourseStudentStatus::Active->value,
                    'teacher_id' => $data->teacher_id,
                    'individual_discount' => $data->individual_discount ?? 0,
                    'enrolled_at' => now(),
                ]);

                $assignedCount++;
            }
        });

        return $assignedCount;
    }
}
