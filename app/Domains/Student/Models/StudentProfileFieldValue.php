<?php

namespace App\Domains\Student\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfileFieldValue extends Model
{
    protected $fillable = [
        'student_id',
        'profile_field_id',
        'value',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function profileField(): BelongsTo
    {
        return $this->belongsTo(ProfileField::class);
    }
}
