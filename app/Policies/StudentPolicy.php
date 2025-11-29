<?php

namespace App\Policies;

use App\Domains\Student\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Student $student): bool
    {
        $teacherCourseIds = $user->getTeacherCourseIds();

        return $student->courses()->whereIn('courses.id', $teacherCourseIds)->exists();
    }

    public function update(User $user, Student $student): bool
    {
        return $this->view($user, $student);
    }
}
