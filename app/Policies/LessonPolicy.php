<?php

namespace App\Policies;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Models\User;

class LessonPolicy
{
    public function viewAny(User $user, Module $module): bool
    {
        return app(CoursePolicy::class)->view($user, $module->course);
    }

    public function view(User $user, Lesson $lesson): bool
    {
        return app(CoursePolicy::class)->view($user, $lesson->module->course);
    }

    public function create(User $user, Module $module): bool
    {
        return app(CoursePolicy::class)->update($user, $module->course);
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return app(CoursePolicy::class)->update($user, $lesson->module->course);
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return app(CoursePolicy::class)->update($user, $lesson->module->course);
    }

    public function reorder(User $user, Module $module): bool
    {
        return app(CoursePolicy::class)->update($user, $module->course);
    }
}
