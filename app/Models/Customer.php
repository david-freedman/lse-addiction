<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
    ];

    /**
     * Один клиент имеет одно описание (профиль).
     */
    public function description()
    {
        return $this->hasOne(CustomerDescription::class);
    }

    /**
     * Один клиент может иметь много логов (действий).
     */
    public function logs()
    {
        return $this->hasMany(CustomerLog::class);
    }
}
