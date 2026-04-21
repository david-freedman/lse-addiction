<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webinars', function (Blueprint $table) {
            $table->string('number', 7)->nullable()->after('slug');
        });

        DB::table('webinars')->whereNull('number')->orderBy('id')->each(function ($webinar) {
            $number = null;
            do {
                $candidate = (string) random_int(1000000, 9999999);
            } while (DB::table('webinars')->where('number', $candidate)->exists());
            $number = $candidate;

            DB::table('webinars')->where('id', $webinar->id)->update(['number' => $number]);
        });

        Schema::table('webinars', function (Blueprint $table) {
            $table->string('number', 7)->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('webinars', function (Blueprint $table) {
            $table->dropUnique(['number']);
            $table->dropColumn('number');
        });
    }
};
