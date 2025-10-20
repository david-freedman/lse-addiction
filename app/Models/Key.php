<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'key',
        'value',
    ];

    // стандартные timestamps (created_at / updated_at)
}
