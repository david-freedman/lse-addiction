<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('purchasable_type');
            $table->unsignedBigInteger('purchasable_id');
            $table->decimal('amount', 10);
            $table->string('currency', 3)->default('UAH');
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index('transaction_number');
            $table->index('status');
            $table->index('created_at');
            $table->index(['purchasable_type', 'purchasable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
