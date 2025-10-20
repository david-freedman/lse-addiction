<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'city',
        'day_birthday',
    ];

    /**
     * Это описание принадлежит одному клиенту.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
