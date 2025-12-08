<?php

namespace App\Models;

use App\Domains\Course\Models\Course;
use App\Domains\Teacher\Models\Teacher;
use App\Domains\Verification\Models\Verification;
use App\Models\Enums\UserRole;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'photo',
        'role',
        'is_active',
    ];

    protected $hidden = [];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'role' => UserRole::class,
        ];
    }

    public function authoredCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'author_id');
    }

    public function teacherProfile(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', UserRole::Teacher);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function verifications(): MorphMany
    {
        return $this->morphMany(Verification::class, 'verifiable');
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isTeacher(): bool
    {
        return $this->role === UserRole::Teacher;
    }

    public function hasRole(UserRole|string|array $roles): bool
    {
        $rolesToCheck = is_array($roles) ? $roles : [$roles];

        foreach ($rolesToCheck as $role) {
            $roleEnum = $role instanceof UserRole ? $role : UserRole::tryFrom($role);
            if ($roleEnum && $this->role === $roleEnum) {
                return true;
            }
        }

        return false;
    }

    public function getTeacherCourseIds(): array
    {
        if (! $this->isTeacher()) {
            return [];
        }

        return $this->teacherProfile?->courses()->pluck('id')->toArray() ?? [];
    }
}
