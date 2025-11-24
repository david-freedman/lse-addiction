<?php

namespace App\Models;

use App\Domains\Course\Models\Course;
use App\Domains\Verification\Models\Verification;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'position',
        'role',
    ];

    protected $hidden = [];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function coachedCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'coach_id');
    }

    public function authoredCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'author_id');
    }

    public function scopeCoaches($query)
    {
        return $query->whereNotNull('photo');
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function verifications(): MorphMany
    {
        return $this->morphMany(Verification::class, 'verifiable');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles, true);
        }

        return $this->role === $roles;
    }
}
