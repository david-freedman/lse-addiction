<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Models\ProfileField;
use App\Domains\Student\Models\Student;
use Illuminate\View\View;

final class EditStudentController
{
    public function __invoke(Student $student): View
    {
        $fields = ProfileField::active()->ordered()->get();
        $student->load('profileFieldValues.profileField');
        $existingProfileFields = $student->profileFieldValues->pluck('value', 'profileField.key')->toArray();

        return view('admin.students.edit', compact('student', 'fields', 'existingProfileFields'));
    }
}
