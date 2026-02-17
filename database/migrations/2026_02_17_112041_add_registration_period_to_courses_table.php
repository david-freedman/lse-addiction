<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->timestamp('registration_starts_at')->nullable()->after('starts_at');
            $table->timestamp('registration_ends_at')->nullable()->after('registration_starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['registration_starts_at', 'registration_ends_at']);
        });
    }
};
