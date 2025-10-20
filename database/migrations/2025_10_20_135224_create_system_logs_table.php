<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->string('type', 100);
            $table->text('value')->nullable();
            $table->timestamp('date_added')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
