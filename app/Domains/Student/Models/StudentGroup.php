<?php

namespace App\Domains\Student\Models;

use App\Domains\Course\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudentGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'course_id',
        'created_by',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_group_members', 'group_id', 'student_id')
            ->withPivot('added_at');
    }
}
