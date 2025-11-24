<div id="purchase-modal" class="fixed inset-0 bg-gray-900/30 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden" onclick="event.stopPropagation()">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Підтвердження покупки</h2>
                <button onclick="closePurchaseModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <p class="text-gray-600 mb-6">Ви збираєтесь придбати курс</p>

            <div class="flex items-start gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <img id="modal-course-image" src="" alt="Course" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <h3 id="modal-course-name" class="font-bold text-gray-900 mb-1 line-clamp-2"></h3>
                    <p class="text-sm text-gray-600">
                        <span>Викладач: <span id="modal-course-instructor"></span></span>
                    </p>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Вартість курсу:</span>
                    <span id="modal-course-price" class="text-xl font-bold text-gray-900"></span>
                </div>
                <div id="modal-discount-row" class="hidden flex justify-between items-center text-green-600">
                    <span>Економія:</span>
                    <span id="modal-discount-amount" class="font-semibold"></span>
                </div>
            </div>

            <form id="purchase-form" method="POST" action="">
                @csrf
                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closePurchaseModal()"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200"
                    >
                        Скасувати
                    </button>
                    <button
                        type="submit"
                        class="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors duration-200"
                    >
                        Оплатити <span id="modal-final-price"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPurchaseModal(courseId, name, instructor, duration, price, discount, imageUrl) {
    const modal = document.getElementById('purchase-modal');
    const form = document.getElementById('purchase-form');
    const modalImage = document.getElementById('modal-course-image');

    document.getElementById('modal-course-name').textContent = name;
    document.getElementById('modal-course-instructor').textContent = instructor || 'Не вказано';
    document.getElementById('modal-course-price').textContent = price;
    document.getElementById('modal-final-price').textContent = price;

    const discountRow = document.getElementById('modal-discount-row');
    if (discount) {
        document.getElementById('modal-discount-amount').textContent = discount;
        discountRow.classList.remove('hidden');
    } else {
        discountRow.classList.add('hidden');
    }

    if (imageUrl) {
        modalImage.src = imageUrl;
        modalImage.classList.remove('hidden');
    } else {
        modalImage.classList.add('hidden');
    }

    form.action = `/student/catalog/${courseId}/purchase`;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePurchaseModal() {
    const modal = document.getElementById('purchase-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('purchase-modal').addEventListener('click', function(event) {
    if (event.target === this) {
        closePurchaseModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePurchaseModal();
    }
});
</script>
