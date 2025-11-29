<?php

namespace App\Console\Commands;

use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Console\Command;

final class CancelAbandonedTransactionsCommand extends Command
{
    protected $signature = 'transactions:cancel-abandoned';

    protected $description = 'Cancel abandoned transactions (pending > 30min, processing > 24h)';

    public function handle(): int
    {
        $pendingCount = Transaction::where('status', TransactionStatus::Pending)
            ->where('created_at', '<', now()->subMinutes(30))
            ->update(['status' => TransactionStatus::Failed]);

        $processingCount = Transaction::where('status', TransactionStatus::Processing)
            ->where('created_at', '<', now()->subHours(24))
            ->update(['status' => TransactionStatus::Failed]);

        $this->info("Cancelled {$pendingCount} pending, {$processingCount} processing transactions.");

        return Command::SUCCESS;
    }
}
