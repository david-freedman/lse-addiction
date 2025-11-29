<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <h3 class="mb-4 text-lg font-bold text-gray-900">Транзакції</h3>

    @if($transactions->isEmpty())
        <p class="text-sm text-gray-500">Немає транзакцій</p>
    @else
        <div class="space-y-2" wire:loading.class="opacity-50">
            @foreach($transactions as $transaction)
                @php
                    $badgeClass = match($transaction->status->color()) {
                        'green' => 'bg-success-100 text-success-700',
                        'orange' => 'bg-warning-100 text-warning-700',
                        'red' => 'bg-error-100 text-error-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3 transition-opacity duration-200">
                    <div>
                        <p class="font-medium text-gray-900">{{ $transaction->purchasable->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">{{ number_format($transaction->amount, 2) }} ₴</p>
                        <span class="inline-block rounded-full px-2 py-0.5 text-xs font-medium {{ $badgeClass }}">
                            {{ $transaction->status->label() }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        @if($transactions->hasPages())
            <div class="mt-4 flex items-center justify-center gap-3">
                <button wire:click="previousPage" wire:loading.attr="disabled" {{ $transactions->onFirstPage() ? 'disabled' : '' }} @class(['rounded-lg border border-gray-300 px-3 py-1.5 text-sm', 'opacity-50 cursor-not-allowed' => $transactions->onFirstPage(), 'hover:bg-gray-100' => !$transactions->onFirstPage()])>
                    ←
                </button>
                <span class="text-sm text-gray-600">{{ $transactions->currentPage() }} / {{ $transactions->lastPage() }}</span>
                <button wire:click="nextPage" wire:loading.attr="disabled" {{ !$transactions->hasMorePages() ? 'disabled' : '' }} @class(['rounded-lg border border-gray-300 px-3 py-1.5 text-sm', 'opacity-50 cursor-not-allowed' => !$transactions->hasMorePages(), 'hover:bg-gray-100' => $transactions->hasMorePages()])>
                    →
                </button>
            </div>
        @endif
    @endif
</div>
