@props(['calendarData'])

<div id="calendar-modal" class="fixed inset-0 bg-gray-900/30 backdrop-blur-md hidden z-50 flex items-center justify-center p-4" onclick="closeCalendarModal()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Календар подій</h2>
                        <p class="text-sm text-gray-500">Перегляньте розклад вебінарів та нових курсів</p>
                    </div>
                </div>
                <button onclick="closeCalendarModal()" class="cursor-pointer text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex-1 flex overflow-hidden">
            <div class="w-3/5 p-6 overflow-y-auto border-r border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <button onclick="previousMonth()" class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <h3 class="text-lg font-bold text-gray-900" id="calendar-month-name"></h3>
                    <button onclick="nextMonth()" class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-7 gap-1 mb-2">
                    <div class="text-center text-sm font-medium text-gray-500 py-2">Пн</div>
                    <div class="text-center text-sm font-medium text-gray-500 py-2">Вт</div>
                    <div class="text-center text-sm font-medium text-gray-500 py-2">Ср</div>
                    <div class="text-center text-sm font-medium text-gray-500 py-2">Чт</div>
                    <div class="text-center text-sm font-medium text-gray-500 py-2">Пт</div>
                    <div class="text-center text-sm font-medium text-gray-500 py-2">Сб</div>
                    <div class="text-center text-sm font-medium text-gray-500 py-2">Нд</div>
                </div>

                <div class="grid grid-cols-7 gap-1" id="calendar-grid"></div>
            </div>

            <div class="w-2/5 p-6 overflow-y-auto bg-gray-50">
                <div id="selected-date-section" class="hidden mb-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-3" id="selected-date-title"></h4>
                    <div id="selected-date-courses" class="space-y-3 mb-3"></div>
                    <div id="selected-date-webinars" class="space-y-3"></div>
                </div>

                <div id="no-events-message" class="hidden mb-6 p-4 bg-white rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-500" id="no-events-text"></p>
                </div>

                @if(!empty($calendarData['upcomingCourses']))
                    <div class="mb-6">
                        <h3 class="text-base font-bold text-gray-900 mb-4">Найближчі курси</h3>
                        <div class="space-y-3" id="upcoming-courses-list">
                            @foreach($calendarData['upcomingCourses'] as $course)
                                <x-student.calendar-course-card :course="$course" />
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4">Найближчі вебінари</h3>
                    <div class="space-y-3" id="upcoming-webinars-list">
                        @foreach($calendarData['upcomingWebinars'] as $webinar)
                            <x-student.calendar-webinar-card :webinar="$webinar" />
                        @endforeach
                        @if(empty($calendarData['upcomingWebinars']))
                            <p class="text-sm text-gray-500">Немає запланованих вебінарів</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const calendarData = @json($calendarData);
    let currentMonthIndex = 0;
    let selectedDate = null;

    window.openCalendarModal = function() {
        const modal = document.getElementById('calendar-modal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        currentMonthIndex = 0;
        selectedDate = null;
        renderCalendar();
        hideSelectedDateSection();
    };

    window.closeCalendarModal = function() {
        const modal = document.getElementById('calendar-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    };

    window.previousMonth = function() {
        if (currentMonthIndex > 0) {
            currentMonthIndex--;
            renderCalendar();
        }
    };

    window.nextMonth = function() {
        if (currentMonthIndex < calendarData.months.length - 1) {
            currentMonthIndex++;
            renderCalendar();
        }
    };

    window.selectCalendarDate = function(dateStr) {
        selectedDate = dateStr;

        document.querySelectorAll('.calendar-date-cell').forEach(cell => {
            cell.classList.remove('ring-2', 'ring-teal-500', 'bg-teal-50');
        });

        const selectedCell = document.querySelector(`[data-date="${dateStr}"]`);
        if (selectedCell) {
            selectedCell.classList.add('ring-2', 'ring-teal-500', 'bg-teal-50');
        }

        const courses = calendarData.coursesByDate[dateStr] || [];
        const webinars = calendarData.webinarsByDate[dateStr] || [];
        updateSelectedDateSection(dateStr, courses, webinars);
    };

    function renderCalendar() {
        const month = calendarData.months[currentMonthIndex];
        document.getElementById('calendar-month-name').textContent = month.name;

        const grid = document.getElementById('calendar-grid');
        grid.innerHTML = '';

        const year = month.year;
        const monthNum = month.month - 1;

        const firstDay = new Date(year, monthNum, 1);
        const lastDay = new Date(year, monthNum + 1, 0);
        const daysInMonth = lastDay.getDate();

        let startDay = firstDay.getDay() - 1;
        if (startDay === -1) startDay = 6;

        const today = new Date().toISOString().split('T')[0];

        for (let i = 0; i < startDay; i++) {
            grid.innerHTML += '<div class="aspect-square"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(monthNum + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const hasCourses = calendarData.datesWithCourses.includes(dateStr);
            const hasWebinars = calendarData.datesWithWebinars.includes(dateStr);
            const isToday = dateStr === today;
            const isSelected = dateStr === selectedDate;

            let cellClasses = 'calendar-date-cell aspect-square rounded-lg flex flex-col items-center justify-center cursor-pointer transition hover:bg-gray-100';

            if (isToday) {
                cellClasses += ' font-bold border border-teal-300';
            }

            if (isSelected) {
                cellClasses += ' ring-2 ring-teal-500 bg-teal-50';
            }

            let indicator = '<div class="flex gap-0.5 mt-1">';
            if (hasCourses) {
                indicator += '<div class="w-1.5 h-1.5 bg-teal-500 rounded-full"></div>';
            }
            if (hasWebinars) {
                indicator += '<div class="w-1.5 h-1.5 bg-purple-500 rounded-full"></div>';
            }
            if (!hasCourses && !hasWebinars) {
                indicator += '<div class="w-1.5 h-1.5"></div>';
            }
            indicator += '</div>';

            grid.innerHTML += `
                <button
                    onclick="selectCalendarDate('${dateStr}')"
                    class="${cellClasses}"
                    data-date="${dateStr}"
                >
                    <span class="text-sm text-gray-900">${day}</span>
                    ${indicator}
                </button>
            `;
        }
    }

    function updateSelectedDateSection(dateStr, courses, webinars) {
        const selectedSection = document.getElementById('selected-date-section');
        const noEventsMessage = document.getElementById('no-events-message');
        const dateTitle = document.getElementById('selected-date-title');
        const coursesList = document.getElementById('selected-date-courses');
        const webinarsList = document.getElementById('selected-date-webinars');

        const formattedDate = formatDateUkrainian(dateStr);

        if (courses.length === 0 && webinars.length === 0) {
            selectedSection.classList.add('hidden');
            noEventsMessage.classList.remove('hidden');
            document.getElementById('no-events-text').textContent = `Немає подій на ${formattedDate}`;
            showAllUpcoming();
        } else {
            noEventsMessage.classList.add('hidden');
            selectedSection.classList.remove('hidden');
            dateTitle.textContent = formattedDate;

            coursesList.innerHTML = courses.map(course => renderCourseCard(course)).join('');
            webinarsList.innerHTML = webinars.map(webinar => renderWebinarCard(webinar)).join('');
            filterUpcoming(courses.map(c => c.id), webinars.map(w => w.id));
        }
    }

    function hideSelectedDateSection() {
        document.getElementById('selected-date-section').classList.add('hidden');
        document.getElementById('no-events-message').classList.add('hidden');
        showAllUpcoming();
    }

    function filterUpcoming(courseIds, webinarIds) {
        const upcomingCoursesList = document.getElementById('upcoming-courses-list');
        if (upcomingCoursesList) {
            upcomingCoursesList.querySelectorAll('[data-course-id]').forEach(card => {
                const cardId = parseInt(card.dataset.courseId);
                card.classList.toggle('hidden', courseIds.includes(cardId));
            });
        }

        const upcomingWebinarsList = document.getElementById('upcoming-webinars-list');
        upcomingWebinarsList.querySelectorAll('[data-webinar-id]').forEach(card => {
            const cardId = parseInt(card.dataset.webinarId);
            card.classList.toggle('hidden', webinarIds.includes(cardId));
        });
    }

    function showAllUpcoming() {
        const upcomingCoursesList = document.getElementById('upcoming-courses-list');
        if (upcomingCoursesList) {
            upcomingCoursesList.querySelectorAll('[data-course-id]').forEach(card => {
                card.classList.remove('hidden');
            });
        }

        const upcomingWebinarsList = document.getElementById('upcoming-webinars-list');
        upcomingWebinarsList.querySelectorAll('[data-webinar-id]').forEach(card => {
            card.classList.remove('hidden');
        });
    }

    function renderCourseCard(course) {
        const avatarHtml = course.teacherPhotoUrl
            ? `<img src="${course.teacherPhotoUrl}" alt="${course.teacherName}" class="w-8 h-8 rounded-full object-cover">`
            : `<div class="w-8 h-8 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xs font-medium">${course.teacherName.charAt(0)}</div>`;

        const labelHtml = course.label
            ? `<span class="px-2 py-0.5 text-xs font-medium bg-teal-100 text-teal-700 rounded">${course.label}</span>`
            : '';

        return `
            <div class="bg-white rounded-lg p-4 border-2 border-teal-500 hover:bg-teal-50 transition cursor-pointer" onclick="window.location.href='/student/catalog/${course.slug}'">
                ${labelHtml ? `<div class="mb-2">${labelHtml}</div>` : ''}
                <h4 class="text-sm font-semibold text-gray-900 mb-3 line-clamp-2">${course.name}</h4>
                <div class="flex items-center gap-2 mb-3">
                    ${avatarHtml}
                    <span class="text-xs text-gray-600">${course.teacherName}</span>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        ${course.formattedDate}
                    </span>
                </div>
            </div>
        `;
    }

    function renderWebinarCard(webinar) {
        const avatarHtml = webinar.teacherPhotoUrl
            ? `<img src="${webinar.teacherPhotoUrl}" alt="${webinar.teacherName}" class="w-8 h-8 rounded-full object-cover">`
            : `<div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-medium">${webinar.teacherName.charAt(0)}</div>`;

        return `
            <div class="bg-white rounded-lg p-4 border-2 border-purple-500 hover:bg-purple-50 transition cursor-pointer" onclick="window.location.href='/student/webinars/${webinar.slug}'">
                <h4 class="text-sm font-semibold text-gray-900 mb-3 line-clamp-2">${webinar.title}</h4>
                <div class="flex items-center gap-2 mb-3">
                    ${avatarHtml}
                    <span class="text-xs text-gray-600">${webinar.teacherName}</span>
                    <span class="text-xs text-gray-400 ml-auto flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        ${webinar.participantsCount}
                    </span>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        ${webinar.formattedDate}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ${webinar.formattedTime}
                    </span>
                    <span class="flex items-center gap-1 whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        ${webinar.formattedDuration}
                    </span>
                </div>
            </div>
        `;
    }

    function formatDateUkrainian(dateStr) {
        const months = [
            'січня', 'лютого', 'березня', 'квітня', 'травня', 'червня',
            'липня', 'серпня', 'вересня', 'жовтня', 'листопада', 'грудня'
        ];
        const [year, month, day] = dateStr.split('-').map(Number);
        return `${day} ${months[month - 1]} ${year} р.`;
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('calendar-modal');
            if (!modal.classList.contains('hidden')) {
                closeCalendarModal();
            }
        }
    });
})();
</script>
