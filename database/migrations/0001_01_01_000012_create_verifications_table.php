<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('verifiable');
            $table->enum('type', ['email', 'phone']);
            $table->string('contact');
            $table->string('code');
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->string('purpose');
            $table->timestamp('created_at');
            $table->timestamp('last_sent_at')->nullable();
            $table->integer('send_count')->default(1);
            $table->timestamp('hourly_reset_at')->nullable();

            $table->index(['contact', 'code']);
            $table->index('expires_at');
            $table->index('last_sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
