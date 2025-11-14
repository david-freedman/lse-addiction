@props([
    'route',
    'type',
    'buttonColor' => 'teal',
    'buttonId' => 'resend-button',
])

<form action="{{ $route }}" method="POST" class="mt-8 pt-6 border-t border-gray-200 text-center">
    @csrf
    <p class="text-sm text-gray-600">
        Не отримали код?
        <button
            id="{{ $buttonId }}"
            type="submit"
            class="text-{{ $buttonColor }}-600 hover:text-{{ $buttonColor }}-700 font-medium disabled:text-gray-400 disabled:cursor-not-allowed"
        >
            Відправити повторно
        </button>
    </p>

    @error('resend')
        <div id="resendError" class="text-sm text-red-600 mt-2">{{ $message }}</div>
    @enderror

    @if(session('status'))
        <div class="text-sm text-green-600 mt-2">{{ session('status') }}</div>
    @endif
</form>

@if(session('next_resend_at'))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resendButton = document.getElementById('{{ $buttonId }}');
            const nextResendAt = {{ session('next_resend_at') }};

            if (window.initResendCountdown) {
                window.initResendCountdown(nextResendAt, '{{ $buttonId }}');
            } else {
                function updateResendTimer() {
                    const now = Math.floor(Date.now() / 1000);
                    const secondsLeft = nextResendAt - now;

                    if (secondsLeft <= 0) {
                        resendButton.textContent = 'Відправити повторно';
                        resendButton.disabled = false;
                        resendButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        const errorDiv = document.getElementById('resendError');
                        if (errorDiv) errorDiv.style.display = 'none';
                        clearInterval(timerInterval);
                        return;
                    }

                    const minutes = Math.floor(secondsLeft / 60);
                    const seconds = secondsLeft % 60;

                    resendButton.textContent = minutes > 0
                        ? `Повторити через ${minutes} хв ${seconds} сек`
                        : `Повторити через ${seconds} сек`;
                    resendButton.disabled = true;
                    resendButton.classList.add('opacity-50', 'cursor-not-allowed');
                }

                updateResendTimer();
                const timerInterval = setInterval(updateResendTimer, 1000);
            }
        });
    </script>
    @endpush
@endif
