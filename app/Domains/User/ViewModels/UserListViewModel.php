<?php

namespace App\Domains\User\ViewModels;

use App\Domains\User\Data\UserFilterData;
use App\Models\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class UserListViewModel
{
    private LengthAwarePaginator $users;

    private UserFilterData $filters;

    public function __construct(UserFilterData $filters, int $perPage = 20)
    {
        $this->filters = $filters;

        $query = User::query()
            ->withCount(['authoredCourses']);

        if ($filters->search) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'ilike', "%{$filters->search}%")
                    ->orWhere('email', 'ilike', "%{$filters->search}%");
            });
        }

        if ($filters->role) {
            $query->where('role', $filters->role);
        }

        if ($filters->is_active !== null) {
            $query->where('is_active', $filters->is_active);
        }

        if ($filters->created_from) {
            $query->whereDate('created_at', '>=', $filters->created_from);
        }

        if ($filters->created_to) {
            $query->whereDate('created_at', '<=', $filters->created_to);
        }

        $this->users = $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function users(): LengthAwarePaginator
    {
        return $this->users;
    }

    public function filters(): UserFilterData
    {
        return $this->filters;
    }

    public function roles(): array
    {
        return UserRole::cases();
    }

    public function hasNoUsers(): bool
    {
        return $this->users->isEmpty();
    }

    public function totalCount(): int
    {
        return $this->users->total();
    }

    public function isFiltered(): bool
    {
        return $this->filters->search !== null
            || $this->filters->role !== null
            || $this->filters->is_active !== null
            || $this->filters->created_from !== null
            || $this->filters->created_to !== null;
    }
}
