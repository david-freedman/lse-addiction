<?php

namespace App\Domains\User\ViewModels;

use App\Models\Enums\UserRole;
use App\Models\User;

readonly class UserDetailViewModel
{
    public function __construct(
        private User $user
    ) {}

    public function user(): User
    {
        return $this->user;
    }

    public function roles(): array
    {
        return UserRole::cases();
    }

    public function authoredCoursesCount(): int
    {
        return $this->user->authoredCourses()->count();
    }
}
