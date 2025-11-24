<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Data\BulkDeleteData;
use App\Domains\Student\Models\Student;
use Illuminate\Support\Facades\DB;

class BulkDeleteStudentsAction
{
    public static function execute(BulkDeleteData $data): int
    {
        return DB::transaction(function () use ($data) {
            $students = Student::whereIn('id', $data->student_ids)->get();

            $deletedCount = 0;
            foreach ($students as $student) {
                if (DeleteStudentAction::execute($student)) {
                    $deletedCount++;
                }
            }

            return $deletedCount;
        });
    }
}
