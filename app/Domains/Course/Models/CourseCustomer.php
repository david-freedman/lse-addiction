<?php

namespace App\Domains\Course\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseCustomer extends Pivot
{
    protected $table = 'course_customer';

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    public $timestamps = false;
}
