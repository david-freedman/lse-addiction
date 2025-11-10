<?php

namespace App\Models;

use App\Domains\Course\Models\Course;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'position',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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
}
