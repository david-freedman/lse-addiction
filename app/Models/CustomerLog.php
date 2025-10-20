<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'value',
        'date_added',
    ];

    public $timestamps = false; // мы явно задали date_added в миграции

    /**
     * Этот лог принадлежит одному клиенту.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
