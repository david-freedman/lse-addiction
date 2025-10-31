<form action="{{ $resendRoute }}" method="POST" class="mt-2 text-center">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <button
        id="resend-button"
        type="submit"
        class="text-blue-500 hover:text-blue-700 text-sm underline disabled:text-gray-400 disabled:cursor-not-allowed disabled:no-underline"
    >
        Відправити код знову
    </button>
    @error('resend')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</form>

@if(session('next_resend_at'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nextResendAt = {{ session('next_resend_at') }};
            if (window.initResendCountdown) {
                window.initResendCountdown(nextResendAt, 'resend-button');
            }
        });
    </script>
@endif
