<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS student_number_seq');
        DB::statement("SELECT setval('student_number_seq', COALESCE((SELECT MAX(number::bigint) FROM students), 0))");
    }

    public function down(): void
    {
        DB::statement('DROP SEQUENCE IF EXISTS student_number_seq');
    }
};
