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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_provider')->nullable()->after('payment_method');
            $table->string('gateway_order_reference')->nullable()->after('payment_provider');
            $table->string('gateway_transaction_id')->nullable()->after('gateway_order_reference');
            $table->jsonb('gateway_response')->nullable()->after('gateway_transaction_id');

            $table->index('payment_provider');
            $table->index('gateway_order_reference');
            $table->index('gateway_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['payment_provider']);
            $table->dropIndex(['gateway_order_reference']);
            $table->dropIndex(['gateway_transaction_id']);
            $table->dropColumn([
                'payment_provider',
                'gateway_order_reference',
                'gateway_transaction_id',
                'gateway_response',
            ]);
        });
    }
};
