<?php

namespace App\Policies;

use App\Domains\Course\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, Course $course): bool
    {
        return $this->canManage($user, $course);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function update(User $user, Course $course): bool
    {
        return $this->canManage($user, $course);
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->canManage($user, $course);
    }

    public function archive(User $user, Course $course): bool
    {
        return $this->canManage($user, $course);
    }

    public function restore(User $user, Course $course): bool
    {
        return $this->canManage($user, $course);
    }

    public function viewStatistics(User $user, Course $course): bool
    {
        return $this->canManage($user, $course);
    }

    private function canManage(User $user, Course $course): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $course->teacher_id === $user->teacherProfile?->id || $course->author_id === $user->id;
    }
}
