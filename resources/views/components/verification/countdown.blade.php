@props([
    'expiresAt',
    'elementId' => 'countdown',
    'label' => 'Код дійсний ще',
    'textColor' => 'teal',
])

<div class="mt-4 text-center">
    <p class="text-sm text-gray-600 mb-2">
        {{ $label }} <span id="{{ $elementId }}" class="font-semibold text-{{ $textColor }}-600"></span>
    </p>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initCountdown !== 'undefined') {
            initCountdown({{ $expiresAt }}, '{{ $elementId }}');
        } else {
            function updateCountdown() {
                const now = Math.floor(Date.now() / 1000);
                const secondsLeft = {{ $expiresAt }} - now;

                if (secondsLeft <= 0) {
                    document.getElementById('{{ $elementId }}').textContent = 'Код застарів';
                    document.getElementById('{{ $elementId }}').classList.add('text-red-600');
                    document.getElementById('{{ $elementId }}').classList.remove('text-{{ $textColor }}-600');
                    clearInterval(countdownInterval);
                    return;
                }

                const minutes = Math.floor(secondsLeft / 60);
                const seconds = secondsLeft % 60;

                document.getElementById('{{ $elementId }}').textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            updateCountdown();
            const countdownInterval = setInterval(updateCountdown, 1000);
        }
    });
</script>
@endpush
