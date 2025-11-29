<?php

namespace Database\Seeders;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Enums\PaymentMethod;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    private array $paymentMethods;

    private array $failureReasons = [
        'Недостатньо коштів',
        'Картку відхилено',
        'Перевищено ліміт',
        'Технічна помилка',
        'Час очікування вичерпано',
        'Невірний CVV код',
    ];

    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('Seeder skipped: No students or courses found. Please run StudentSeeder and CourseSeeder first.');

            return;
        }

        $this->paymentMethods = PaymentMethod::cases();

        $transactionNumber = 1;
        $totalAmount = 0;
        $statusCounts = ['completed' => 0, 'pending' => 0, 'failed' => 0];

        foreach ($students as $student) {
            $transactionsCount = rand(3, 8);

            for ($i = 0; $i < $transactionsCount; $i++) {
                $course = $courses->random();
                $createdAt = $this->generateRandomDate();
                $status = $this->determineStatus($createdAt);
                $paymentMethod = $this->paymentMethods[array_rand($this->paymentMethods)];
                $amount = $course->price > 0 ? $course->price : rand(1000, 5000);

                $paymentReference = null;
                if (in_array($paymentMethod, [PaymentMethod::Visa, PaymentMethod::Mastercard])) {
                    $paymentReference = (string) rand(1000, 9999);
                }

                $completedAt = null;
                $metadata = null;

                if ($status === TransactionStatus::Completed) {
                    $completedAt = $createdAt->copy()->addMinutes(rand(1, 30));
                    $totalAmount += $amount;
                    $statusCounts['completed']++;
                } elseif ($status === TransactionStatus::Failed) {
                    $metadata = ['failure_reason' => $this->failureReasons[array_rand($this->failureReasons)]];
                    $statusCounts['failed']++;
                } else {
                    $statusCounts['pending']++;
                }

                Transaction::create([
                    'transaction_number' => 'TXN-'.str_pad($transactionNumber, 4, '0', STR_PAD_LEFT),
                    'student_id' => $student->id,
                    'purchasable_type' => Course::class,
                    'purchasable_id' => $course->id,
                    'amount' => $amount,
                    'status' => $status,
                    'payment_method' => $paymentMethod,
                    'payment_reference' => $paymentReference,
                    'completed_at' => $completedAt,
                    'metadata' => $metadata,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $transactionNumber++;
            }
        }

        $total = $transactionNumber - 1;
        $this->command->info("Created {$total} transactions:");
        $this->command->info("  - Completed: {$statusCounts['completed']} (".round($statusCounts['completed'] / $total * 100).'%)');
        $this->command->info("  - Pending: {$statusCounts['pending']} (".round($statusCounts['pending'] / $total * 100).'%)');
        $this->command->info("  - Failed: {$statusCounts['failed']} (".round($statusCounts['failed'] / $total * 100).'%)');
        $this->command->info('  - Total completed amount: '.number_format($totalAmount, 0, ',', ' ').' UAH');
    }

    private function generateRandomDate(): Carbon
    {
        $rand = rand(1, 100);

        if ($rand <= 15) {
            $daysAgo = rand(0, 6);
        } elseif ($rand <= 40) {
            $daysAgo = rand(7, 29);
        } elseif ($rand <= 70) {
            $daysAgo = rand(30, 89);
        } elseif ($rand <= 90) {
            $daysAgo = rand(90, 364);
        } else {
            $daysAgo = rand(365, 730);
        }

        $date = now()->subDays($daysAgo);

        if ($daysAgo > 0) {
            $date->setHour(rand(8, 22))->setMinute(rand(0, 59))->setSecond(rand(0, 59));
        }

        return $date;
    }

    private function determineStatus(Carbon $date): TransactionStatus
    {
        if ($date->isToday()) {
            $rand = rand(1, 100);
            if ($rand <= 60) {
                return TransactionStatus::Pending;
            } elseif ($rand <= 90) {
                return TransactionStatus::Completed;
            }

            return TransactionStatus::Failed;
        }

        $rand = rand(1, 100);
        if ($rand <= 89) {
            return TransactionStatus::Completed;
        }

        return TransactionStatus::Failed;
    }
}
