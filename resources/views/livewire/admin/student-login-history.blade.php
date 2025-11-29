<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <h3 class="mb-4 text-lg font-bold text-gray-900">Історія входів</h3>

    @if($loginHistory->isEmpty())
        <p class="text-sm text-gray-500">Немає записів</p>
    @else
        <div class="space-y-2" wire:loading.class="opacity-50">
            @foreach($loginHistory as $log)
                @php $ua = $this->parseUserAgent($log->user_agent) @endphp
                <div class="rounded-lg border border-gray-200 p-3 transition-opacity duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $log->created_at->format('d.m.Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500">{{ $ua['os'] }} · {{ $ua['browser'] }} · {{ $ua['device'] }}</p>
                            <p class="text-xs text-gray-500">IP: {{ $log->ip_address ?? 'N/A' }}</p>
                        </div>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $log->activity_type->value === 'student.login.success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $log->activity_type->value === 'student.login.success' ? 'Успішно' : 'Помилка' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        @if($loginHistory->hasPages())
            <div class="mt-4 flex items-center justify-center gap-3">
                <button wire:click="previousPage" wire:loading.attr="disabled" {{ $loginHistory->onFirstPage() ? 'disabled' : '' }} @class(['rounded-lg border border-gray-300 px-3 py-1.5 text-sm', 'opacity-50 cursor-not-allowed' => $loginHistory->onFirstPage(), 'hover:bg-gray-100' => !$loginHistory->onFirstPage()])>
                    ←
                </button>
                <span class="text-sm text-gray-600">{{ $loginHistory->currentPage() }} / {{ $loginHistory->lastPage() }}</span>
                <button wire:click="nextPage" wire:loading.attr="disabled" {{ !$loginHistory->hasMorePages() ? 'disabled' : '' }} @class(['rounded-lg border border-gray-300 px-3 py-1.5 text-sm', 'opacity-50 cursor-not-allowed' => !$loginHistory->hasMorePages(), 'hover:bg-gray-100' => $loginHistory->hasMorePages()])>
                    →
                </button>
            </div>
        @endif
    @endif
</div>
