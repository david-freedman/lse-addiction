<?php

namespace App\Domains\Student\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialty extends Model
{
    protected $fillable = ['name'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_specialty');
    }
}
