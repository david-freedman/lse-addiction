<div id="delete-account-modal" class="fixed inset-0 bg-gray-900/30 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden" onclick="event.stopPropagation()">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-xl font-bold text-red-600">Видалення акаунту</h2>
                <button type="button" onclick="closeDeleteAccountModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-gray-600 text-center mb-4">Ви впевнені, що хочете видалити свій акаунт?</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700">
                    <p class="font-semibold mb-2">Увага! Ця дія незворотна:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Ваш доступ до курсів буде втрачено</li>
                        <li>Весь прогрес навчання буде видалено</li>
                        <li>Сертифікати будуть назавжди видалені</li>
                    </ul>
                </div>
            </div>

            <form id="delete-account-form" method="POST" action="{{ route('student.profile.delete') }}">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteAccountModal()" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Скасувати
                    </button>
                    <button type="submit" id="delete-account-btn" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200 disabled:bg-red-400 disabled:cursor-not-allowed">
                        <span id="delete-btn-text">Видалити акаунт</span>
                        <span id="delete-btn-loading" class="hidden items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Видалення...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteAccountModal() {
    const modal = document.getElementById('delete-account-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteAccountModal() {
    const modal = document.getElementById('delete-account-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    const submitBtn = document.getElementById('delete-account-btn');
    const btnText = document.getElementById('delete-btn-text');
    const btnLoading = document.getElementById('delete-btn-loading');
    submitBtn.disabled = false;
    btnText.classList.remove('hidden');
    btnLoading.classList.add('hidden');
    btnLoading.classList.remove('flex');
}

document.getElementById('delete-account-modal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeDeleteAccountModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('delete-account-modal');
        if (!modal.classList.contains('hidden')) {
            closeDeleteAccountModal();
        }
    }
});

document.getElementById('delete-account-form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('delete-account-btn');
    const btnText = document.getElementById('delete-btn-text');
    const btnLoading = document.getElementById('delete-btn-loading');
    if (submitBtn.disabled) {
        e.preventDefault();
        return false;
    }
    submitBtn.disabled = true;
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    btnLoading.classList.add('flex');
});
</script>
