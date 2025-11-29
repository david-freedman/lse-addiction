<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('purchasable_type');
            $table->unsignedBigInteger('purchasable_id');
            $table->decimal('amount', 10);
            $table->string('currency', 3)->default('UAH');
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_provider')->nullable();
            $table->string('gateway_order_reference')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->jsonb('gateway_response')->nullable();
            $table->string('payment_reference')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index('transaction_number');
            $table->index('status');
            $table->index('created_at');
            $table->index(['purchasable_type', 'purchasable_id']);
            $table->index('payment_provider');
            $table->index('gateway_order_reference');
            $table->index('gateway_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
