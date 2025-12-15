<?php

namespace App\Policies;

use App\Domains\Homework\Models\Homework;
use App\Domains\Lesson\Models\Lesson;
use App\Models\User;

class HomeworkPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, Homework $homework): bool
    {
        return $this->canManage($user, $homework);
    }

    public function create(User $user, Lesson $lesson): bool
    {
        return app(LessonPolicy::class)->update($user, $lesson);
    }

    public function update(User $user, Homework $homework): bool
    {
        return $this->canManage($user, $homework);
    }

    public function delete(User $user, Homework $homework): bool
    {
        return $this->canManage($user, $homework);
    }

    private function canManage(User $user, Homework $homework): bool
    {
        $course = $homework->lesson->module->course;

        return app(CoursePolicy::class)->update($user, $course);
    }
}
