<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
        'date_added',
    ];

    public $timestamps = false; // используем date_added вместо created_at
}
