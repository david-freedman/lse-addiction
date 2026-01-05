<?php

namespace App\Policies;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Models\Module;
use App\Models\User;

class ModulePolicy
{
    public function viewAny(User $user, Course $course): bool
    {
        return app(CoursePolicy::class)->view($user, $course);
    }

    public function view(User $user, Module $module): bool
    {
        return app(CoursePolicy::class)->view($user, $module->course);
    }

    public function create(User $user, Course $course): bool
    {
        return app(CoursePolicy::class)->update($user, $course);
    }

    public function update(User $user, Module $module): bool
    {
        return app(CoursePolicy::class)->update($user, $module->course);
    }

    public function delete(User $user, Module $module): bool
    {
        return app(CoursePolicy::class)->update($user, $module->course);
    }

    public function reorder(User $user, Course $course): bool
    {
        return app(CoursePolicy::class)->update($user, $course);
    }
}
