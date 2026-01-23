<div id="register-modal" class="fixed inset-0 bg-gray-900/30 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden" onclick="event.stopPropagation()">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 id="modal-header-title" class="text-xl font-bold text-gray-900">Реєстрація на вебінар</h2>
                    <p id="modal-header-subtitle" class="text-gray-500 text-sm mt-1">Підтвердіть Вашу участь</p>
                </div>
                <button onclick="closeRegisterModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex items-start gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <img id="modal-webinar-image" src="" alt="Webinar" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                <div id="modal-webinar-placeholder" class="w-20 h-20 rounded-lg flex-shrink-0 bg-gradient-to-br from-teal-400 to-teal-600 hidden"></div>
                <div class="flex-1 min-w-0">
                    <h3 id="modal-webinar-title" class="font-bold text-gray-900 mb-1 line-clamp-2"></h3>
                    <p id="modal-webinar-teacher" class="text-sm text-gray-600"></p>
                    <p id="modal-webinar-datetime" class="text-sm text-gray-500 mt-1"></p>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Тривалість:</span>
                    <span id="modal-webinar-duration" class="font-semibold text-gray-900"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Вільних місць:</span>
                    <span id="modal-webinar-spots" class="font-semibold text-gray-900"></span>
                </div>
                <div class="flex justify-between items-center border-t pt-3">
                    <span class="text-gray-900 font-semibold">Вартість</span>
                    <span id="modal-webinar-price" class="text-xl font-bold"></span>
                </div>
            </div>

            <form id="register-form" method="POST" action="">
                @csrf
                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeRegisterModal()"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200"
                    >
                        Скасувати
                    </button>
                    <button
                        type="submit"
                        id="register-submit-btn"
                        class="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors duration-200 disabled:bg-teal-400 disabled:cursor-not-allowed"
                    >
                        <span id="register-btn-text"></span>
                        <span id="register-btn-loading" class="hidden items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Обробка...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRegisterModal(slug, title, teacher, datetime, duration, spots, price, imageUrl, isFree) {
    const modal = document.getElementById('register-modal');
    const form = document.getElementById('register-form');
    const modalImage = document.getElementById('modal-webinar-image');
    const modalPlaceholder = document.getElementById('modal-webinar-placeholder');
    const isRecording = datetime === 'Запис';

    document.getElementById('modal-header-title').textContent = 'Реєстрація на вебінар';
    document.getElementById('modal-header-subtitle').textContent = isRecording ? 'Підтвердіть отримання доступу' : 'Підтвердіть Вашу участь';
    document.getElementById('modal-webinar-title').textContent = title;
    document.getElementById('modal-webinar-teacher').textContent = teacher;
    document.getElementById('modal-webinar-datetime').textContent = datetime;
    document.getElementById('modal-webinar-duration').textContent = duration;
    document.getElementById('modal-webinar-spots').textContent = spots;

    const priceEl = document.getElementById('modal-webinar-price');
    if (isFree) {
        priceEl.textContent = 'Безкоштовно';
        priceEl.classList.remove('text-teal-600');
        priceEl.classList.add('text-green-600');
        document.getElementById('register-btn-text').textContent =  'Зареєструватися';
    } else {
        priceEl.textContent = price;
        priceEl.classList.remove('text-green-600');
        priceEl.classList.add('text-teal-600');
        document.getElementById('register-btn-text').textContent = 'Зареєструватися';
    }

    if (imageUrl) {
        modalImage.src = imageUrl;
        modalImage.classList.remove('hidden');
        modalPlaceholder.classList.add('hidden');
    } else {
        modalImage.classList.add('hidden');
        modalPlaceholder.classList.remove('hidden');
    }

    form.action = `/student/webinars/${slug}/register`;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRegisterModal() {
    const modal = document.getElementById('register-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';

    const submitBtn = document.getElementById('register-submit-btn');
    const btnText = document.getElementById('register-btn-text');
    const btnLoading = document.getElementById('register-btn-loading');
    submitBtn.disabled = false;
    btnText.classList.remove('hidden');
    btnLoading.classList.add('hidden');
    btnLoading.classList.remove('flex');
}

document.getElementById('register-modal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeRegisterModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('register-modal');
        if (!modal.classList.contains('hidden')) {
            closeRegisterModal();
        }
    }
});

document.getElementById('register-form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('register-submit-btn');
    const btnText = document.getElementById('register-btn-text');
    const btnLoading = document.getElementById('register-btn-loading');

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
