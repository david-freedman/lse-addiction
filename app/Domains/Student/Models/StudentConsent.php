<?php

namespace App\Domains\Student\Models;

use App\Domains\Student\Enums\ConsentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentConsent extends Model
{
    protected $fillable = [
        'student_id',
        'consent_type',
        'ip_address',
        'document_version',
        'consented_at',
    ];

    protected function casts(): array
    {
        return [
            'consent_type' => ConsentType::class,
            'consented_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
