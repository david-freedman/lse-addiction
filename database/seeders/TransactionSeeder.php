<?php

namespace Database\Seeders;

use App\Domains\Course\Models\Course;
use App\Domains\Customer\Models\Customer;
use App\Domains\Transaction\Enums\PaymentMethod;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $courses = Course::all();

        if ($customers->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('Seeder skipped: No customers or courses found. Please run CustomerSeeder and CourseSeeder first.');
            return;
        }

        $customer = $customers->first();

        $transactions = [
            [
                'transaction_number' => 'TXN-001',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 2500.00,
                'status' => TransactionStatus::Completed,
                'payment_method' => PaymentMethod::Visa,
                'payment_reference' => '4242',
                'completed_at' => now()->subMonths(2)->subDays(5),
                'created_at' => now()->subMonths(2)->subDays(5),
            ],
            [
                'transaction_number' => 'TXN-002',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 5300.00,
                'status' => TransactionStatus::Completed,
                'payment_method' => PaymentMethod::Visa,
                'payment_reference' => '4242',
                'completed_at' => now()->subMonths(1)->subDays(20),
                'created_at' => now()->subMonths(1)->subDays(20),
            ],
            [
                'transaction_number' => 'TXN-003',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 2500.00,
                'status' => TransactionStatus::Processing,
                'payment_method' => PaymentMethod::Mastercard,
                'payment_reference' => '8888',
                'completed_at' => null,
                'created_at' => now()->subMonths(1)->subDays(10),
            ],
            [
                'transaction_number' => 'TXN-004',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 2500.00,
                'status' => TransactionStatus::Completed,
                'payment_method' => PaymentMethod::Visa,
                'payment_reference' => '4242',
                'completed_at' => now()->subDays(30),
                'created_at' => now()->subDays(30),
            ],
            [
                'transaction_number' => 'TXN-005',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 6700.00,
                'status' => TransactionStatus::Failed,
                'payment_method' => PaymentMethod::ApplePay,
                'payment_reference' => null,
                'completed_at' => null,
                'created_at' => now()->subDays(23),
                'metadata' => ['failure_reason' => 'Недостатньо коштів'],
            ],
            [
                'transaction_number' => 'TXN-006',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 2500.00,
                'status' => TransactionStatus::Completed,
                'payment_method' => PaymentMethod::Mastercard,
                'payment_reference' => '8888',
                'completed_at' => now()->subDays(18),
                'created_at' => now()->subDays(18),
            ],
            [
                'transaction_number' => 'TXN-007',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 2500.00,
                'status' => TransactionStatus::Completed,
                'payment_method' => PaymentMethod::Visa,
                'payment_reference' => '4242',
                'completed_at' => now()->subDays(15),
                'created_at' => now()->subDays(15),
            ],
            [
                'transaction_number' => 'TXN-008',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 2500.00,
                'status' => TransactionStatus::Completed,
                'payment_method' => PaymentMethod::GooglePay,
                'payment_reference' => null,
                'completed_at' => now()->subDays(7),
                'created_at' => now()->subDays(7),
            ],
            [
                'transaction_number' => 'TXN-009',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 1800.00,
                'status' => TransactionStatus::Completed,
                'payment_method' => PaymentMethod::Visa,
                'payment_reference' => '4242',
                'completed_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
            ],
            [
                'transaction_number' => 'TXN-010',
                'customer_id' => $customer->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $courses->random()->id,
                'amount' => 3700.00,
                'status' => TransactionStatus::Completed,
                'payment_method' => PaymentMethod::Mastercard,
                'payment_reference' => '8888',
                'completed_at' => now()->subDays(1),
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($transactions as $transactionData) {
            Transaction::create($transactionData);
        }

        $this->command->info('Created 10 transactions with total completed amount: 23,300 UAH');
    }
}
