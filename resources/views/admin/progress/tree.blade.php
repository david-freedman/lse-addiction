@extends('admin.layouts.admin')

@section('title', 'Прогрес студентів')

@section('content')
<div class="mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Прогрес студентів</h1>
    <p class="text-gray-500">Детальний перегляд прогресу по курсам, модулям та урокам</p>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6 mb-6"
     x-data="{
         courseId: {{ $viewModel->selectedCourse()?->id ?? 'null' }},
         studentId: {{ $viewModel->selectedStudent()?->id ?? 'null' }},
         students: {{ json_encode($viewModel->students()->map(fn($s) => ['id' => $s->id, 'name' => $s->surname . ' ' . $s->name, 'email' => (string) $s->email])) }},
         loading: false,

         async loadStudents() {
             if (!this.courseId) {
                 this.students = [];
                 this.studentId = null;
                 return;
             }
             this.loading = true;
             try {
                 const response = await fetch(`/admin/progress/api/students/${this.courseId}`, {
                     headers: { 'Accept': 'application/json' }
                 });
                 if (!response.ok) {
                     this.students = [];
                     return;
                 }
                 this.students = await response.json();
                 this.studentId = null;
             } catch (error) {
                 this.students = [];
             } finally {
                 this.loading = false;
             }
         }
     }">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <div class="min-w-[250px]">
            <label class="mb-2 block text-sm font-medium text-gray-700">Курс</label>
            <select name="course_id" x-model="courseId" @change="loadStudents()"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                <option value="">Оберіть курс</option>
                @foreach($viewModel->courses() as $course)
                    <option value="{{ $course->id }}" @selected($viewModel->selectedCourse()?->id === $course->id)>
                        {{ $course->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="min-w-[300px]">
            <label class="mb-2 block text-sm font-medium text-gray-700">Студент</label>
            <select name="student_id" x-model="studentId" :disabled="!courseId || loading"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white disabled:opacity-50">
                <option value="">Оберіть студента</option>
                <template x-for="student in students" :key="student.id">
                    <option :value="student.id" x-text="student.name + ' (' + student.email + ')'"></option>
                </template>
            </select>
        </div>

        <button type="submit" :disabled="!courseId"
                class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed">
            Показати прогрес
        </button>

        @if($viewModel->hasCourseSelection())
            <a href="{{ route('admin.progress.tree') }}"
               class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Скинути
            </a>
        @endif
    </form>
</div>

@if($viewModel->hasCourseSelection() && !$viewModel->selectedStudent())
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                Студенти курсу: {{ $viewModel->selectedCourse()->name }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $viewModel->studentsProgress()->count() }} {{ trans_choice('студент|студенти|студентів', $viewModel->studentsProgress()->count()) }}
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Студент</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Прогрес</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Остання активність</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($viewModel->studentsProgress() as $data)
                        <tr class="hover:bg-gray-50 cursor-pointer"
                            onclick="window.location='{{ route('admin.progress.tree', ['course_id' => $viewModel->selectedCourse()->id, 'student_id' => $data['student']->id]) }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-900">{{ $data['student']->surname }} {{ $data['student']->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $data['student']->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-brand-500" style="width: {{ $data['progress_percentage'] }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-600">{{ round($data['progress_percentage'], 1) }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $data['status']->color() === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $data['status']->color() === 'blue' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $data['status']->color() === 'gray' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $data['status']->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($data['last_activity_at'])
                                    {{ $data['last_activity_at']->format('d.m.Y H:i') }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@elseif($viewModel->hasSelection())
    @php $tree = $viewModel->progressTree(); @endphp

    @if($tree)
        <div class="rounded-2xl border border-gray-200 bg-white p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $tree['course']->name }}</h2>
                    <p class="text-sm text-gray-500">
                        Студент: {{ $viewModel->selectedStudent()->surname }} {{ $viewModel->selectedStudent()->name }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Загальний прогрес</p>
                    <div class="flex items-center gap-3">
                        <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-500 transition-all" style="width: {{ $tree['progress_percentage'] }}%"></div>
                        </div>
                        <span class="text-lg font-bold text-brand-600">{{ round($tree['progress_percentage'], 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4" x-data="{ openModules: {} }">
            @foreach($tree['modules'] as $moduleIndex => $moduleData)
                <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
                    <button @click="openModules[{{ $moduleIndex }}] = !openModules[{{ $moduleIndex }}]"
                            class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full
                                {{ $moduleData['status']->color() === 'green' ? 'bg-green-100 text-green-600' : '' }}
                                {{ $moduleData['status']->color() === 'blue' ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $moduleData['status']->color() === 'gray' ? 'bg-gray-100 text-gray-600' : '' }}">
                                @if($moduleData['status']->color() === 'green')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($moduleData['status']->color() === 'blue')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="text-left">
                                <h3 class="font-semibold text-gray-900">{{ $moduleData['module']->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $moduleData['lessons']->count() }} {{ trans_choice('урок|уроки|уроків', $moduleData['lessons']->count()) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand-500" style="width: {{ $moduleData['progress_percentage'] }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-600">{{ round($moduleData['progress_percentage'], 1) }}%</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="openModules[{{ $moduleIndex }}] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    <div x-show="openModules[{{ $moduleIndex }}]" x-collapse class="border-t border-gray-200">
                        <div class="divide-y divide-gray-100">
                            @foreach($moduleData['lessons'] as $lessonData)
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-3 flex-1">
                                            <div class="flex items-center justify-center w-6 h-6 rounded-full mt-0.5
                                                {{ $lessonData['status']->color() === 'green' ? 'bg-green-100 text-green-600' : '' }}
                                                {{ $lessonData['status']->color() === 'blue' ? 'bg-blue-100 text-blue-600' : '' }}
                                                {{ $lessonData['status']->color() === 'gray' ? 'bg-gray-100 text-gray-400' : '' }}">
                                                @if($lessonData['status']->color() === 'green')
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @elseif($lessonData['status']->color() === 'blue')
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                                    </svg>
                                                @else
                                                    <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $lessonData['lesson']->name }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $lessonData['status']->label() }}
                                                    @if($lessonData['completed_at'])
                                                        · {{ $lessonData['completed_at']->format('d.m.Y') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-6 text-sm">
                                            @if($lessonData['quiz']['exists'])
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-500">Тест:</span>
                                                    @if($lessonData['quiz']['attempt_id'])
                                                        <a href="{{ route('admin.quiz-attempts.show', $lessonData['quiz']['attempt_id']) }}"
                                                           class="inline-flex items-center gap-1 font-medium {{ $lessonData['quiz']['passed'] ? 'text-green-600' : 'text-red-600' }} hover:underline">
                                                            {{ $lessonData['quiz']['score_percentage'] }}%
                                                            ({{ $lessonData['quiz']['score'] }}/{{ $lessonData['quiz']['max_score'] }})
                                                            @if($lessonData['quiz']['passed'])
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                            @else
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            @endif
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">не пройдено</span>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($lessonData['homework']['exists'])
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-500">ДЗ{{ $lessonData['homework']['is_required'] ? '*' : '' }}:</span>
                                                    @if($lessonData['homework']['submission_id'])
                                                        <a href="{{ route('admin.homework.submissions.show', $lessonData['homework']['submission_id']) }}"
                                                           class="inline-flex items-center gap-1 font-medium text-{{ $lessonData['homework']['status']->color() }}-600 hover:underline">
                                                            {{ $lessonData['homework']['status']->label() }}
                                                            @if($lessonData['homework']['score'] !== null)
                                                                ({{ $lessonData['homework']['score'] }}/{{ $lessonData['homework']['max_points'] }})
                                                            @endif
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">не здано</span>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(!$lessonData['quiz']['exists'] && !$lessonData['homework']['exists'])
                                                <span class="text-gray-400 text-xs">Без завдань</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Немає даних</h3>
            <p class="mt-2 text-gray-500">Студент не записаний на цей курс або не має прогресу</p>
        </div>
    @endif
@else
    <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Оберіть курс</h3>
        <p class="mt-2 text-gray-500">Щоб переглянути прогрес студентів, оберіть курс у фільтрах вище</p>
    </div>
@endif
@endsection
