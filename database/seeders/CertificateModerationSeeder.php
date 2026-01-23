<?php

namespace Database\Seeders;

use App\Domains\Certificate\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateModerationSeeder extends Seeder
{
    public function run(): void
    {
        Certificate::query()
            ->whereNull('published_at')
            ->whereNull('revoked_at')
            ->whereNull('deleted_at')
            ->update(['published_at' => \DB::raw('issued_at')]);
    }
}
