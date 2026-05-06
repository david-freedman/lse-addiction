<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->timestamp('profile_fields_completed_at')->nullable()->after('last_login_at');
        });

        // Backfill existing students so they are not retroactively blocked
        DB::table('students')->update(['profile_fields_completed_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('profile_fields_completed_at');
        });
    }
};
