<?php

namespace App\Domains\Webinar\Models;

use App\Domains\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WebinarStudent extends Pivot
{
    protected $table = 'webinar_student';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'attended_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
