<?php

namespace App\Policies;

use App\Domains\Course\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Course $course): bool
    {
        return $course->teacher_id === $user->teacherProfile?->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Course $course): bool
    {
        return $course->teacher_id === $user->teacherProfile?->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $course->teacher_id === $user->teacherProfile?->id;
    }
}
