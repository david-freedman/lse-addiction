<div class="mt-4 text-center">
    <p class="text-sm text-gray-600 mb-2">Код дійсний ще <span id="countdown" class="font-semibold text-blue-600"></span></p>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initCountdown !== 'undefined') {
            initCountdown({{ session($sessionKey, now()->addMinutes(15)->timestamp) }});
        }
    });
</script>
@endpush
