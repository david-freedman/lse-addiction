<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // customer_id
            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();
            $table->timestamps(); // date_added, date_modified
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
