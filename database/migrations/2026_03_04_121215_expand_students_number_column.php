<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE students ALTER COLUMN number TYPE varchar(10)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE students ALTER COLUMN number TYPE varchar(6)');
    }
};
