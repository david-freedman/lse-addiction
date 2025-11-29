@extends('admin.layouts.admin')

@section('title', $student->name . ' ' . $student->surname)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">{{ $student->name }} {{ $student->surname }}</h1>
        @if($student->trashed())
            <span class="mt-2 inline-block rounded-full bg-danger-100 px-3 py-1 text-xs font-medium text-danger-700">Видалений</span>
        @elseif($student->isNew())
            <span class="mt-2 inline-block rounded-full bg-success-100 px-3 py-1 text-xs font-medium text-success-700">Новий</span>
        @endif
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.students.edit', $student) }}" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
            Редагувати
        </a>
        <a href="{{ route('admin.students.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
            До списку
        </a>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-1">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="text-center">
                @if($student->profile_photo)
                    <img src="{{ Storage::url($student->profile_photo) }}" alt="{{ $student->name }}" class="mx-auto mb-4 h-32 w-32 rounded-full object-cover">
                @else
                    <div class="mx-auto mb-4 flex h-32 w-32 items-center justify-center rounded-full bg-gray-200 text-4xl font-bold text-gray-600">
                        {{ mb_substr($student->name, 0, 1) }}{{ mb_substr($student->surname, 0, 1) }}
                    </div>
                @endif
                <h3 class="text-lg font-bold text-gray-900">{{ $student->name }} {{ $student->surname }}</h3>
                <p class="text-sm text-gray-500">ID: {{ $student->id }}</p>
            </div>

            <div class="mt-6 space-y-3">
                <div>
                    <p class="text-xs font-medium text-gray-500">Email</p>
                    <p class="text-sm text-gray-900">{{ $student->email->value }}</p>
                    @if($student->email_verified_at)
                        <span class="text-xs text-success-600">✓ Підтверджено</span>
                    @else
                        <span class="text-xs text-gray-500">Не підтверджено</span>
                    @endif
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500">Телефон</p>
                    <p class="text-sm text-gray-900">{{ $student->phone->value }}</p>
                    @if($student->phone_verified_at)
                        <span class="text-xs text-success-600">✓ Підтверджено</span>
                    @else
                        <span class="text-xs text-gray-500">Не підтверджено</span>
                    @endif
                </div>

                @if($student->birthday)
                    <div>
                        <p class="text-xs font-medium text-gray-500">Дата народження</p>
                        <p class="text-sm text-gray-900">{{ $student->birthday->format('d.m.Y') }}</p>
                    </div>
                @endif

                @if($student->city)
                    <div>
                        <p class="text-xs font-medium text-gray-500">Місто</p>
                        <p class="text-sm text-gray-900">{{ $student->city }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-xs font-medium text-gray-500">Зареєстровано</p>
                    <p class="text-sm text-gray-900">{{ $student->created_at->format('d.m.Y H:i') }}</p>
                </div>

                @if($viewModel->lastLoginAt())
                    <div>
                        <p class="text-xs font-medium text-gray-500">Останній вхід</p>
                        <p class="text-sm text-gray-900">{{ $viewModel->lastLoginAt() }}</p>
                    </div>
                @endif
            </div>

            @if($viewModel->hasProfileFields())
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h4 class="mb-3 text-sm font-semibold text-gray-900">Додаткова інформація</h4>
                    <div class="space-y-3">
                        @foreach($viewModel->profileFields() as $label => $value)
                            <div>
                                <p class="text-xs font-medium text-gray-500">{{ $label }}</p>
                                <p class="text-sm text-gray-900">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($student->trashed())
                <form action="{{ route('admin.students.restore', $student->id) }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full rounded-lg bg-success-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-success-600">
                        Відновити студента
                    </button>
                </form>
            @else
                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="mt-6" onsubmit="return confirm('Видалити студента?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg bg-error-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-error-600">
                        Видалити студента
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="mb-4 text-lg font-bold text-gray-900">Курси ({{ $viewModel->enrolledCoursesCount() }})</h3>

                @if($viewModel->hasEnrolledCourses())
                    <div class="space-y-3">
                        @foreach($viewModel->enrolledCourses() as $course)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $course->name }}</h4>
                                        @if($course->pivot->teacher)
                                            <p class="text-sm text-gray-500">Викладач: {{ $course->pivot->teacher->name }}</p>
                                        @endif
                                        <div class="mt-2 flex gap-4 text-xs text-gray-500">
                                            <span>Прогрес: {{ $course->pivot->formattedProgress() }} ({{ $course->pivot->progressPercentage() }}%)</span>
                                            {{-- Temporarily hidden: Individual discount display --}}
                                            {{-- @if($course->pivot->individual_discount > 0)
                                                <span class="text-brand-600">Знижка: {{ $course->pivot->individual_discount }}%</span>
                                            @endif --}}
                                        </div>
                                        @if($course->pivot->notes)
                                            <p class="mt-2 text-sm text-gray-600">{{ $course->pivot->notes }}</p>
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.students.unenroll-from-course', [$student, $course]) }}" method="POST" onsubmit="return confirm('Відписати від курсу?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-error-600 hover:text-error-700">Відписати</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Студент не записаний на жоден курс</p>
                @endif

                @if($viewModel->hasAvailableCourses())
                    <details class="mt-4">
                        <summary class="cursor-pointer text-sm font-medium text-brand-600">Призначити на курс</summary>
                        <form action="{{ route('admin.students.assign-to-course', $student) }}" method="POST" class="mt-4 space-y-4 rounded-lg bg-gray-50 p-4">
                            @csrf
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Курс</label>
                                <select name="courses[0][course_id]" required class="w-full rounded-lg border border-gray-300 px-4 py-2">
                                    @foreach($viewModel->availableCourses() as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Викладач</label>
                                <select name="courses[0][teacher_id]" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                                    <option value="">Без викладача</option>
                                    @foreach($viewModel->teachers() as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Temporarily hidden: Individual discount form field --}}
                            {{-- <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Персональна знижка (%)</label>
                                <input type="number" name="courses[0][individual_discount]" min="0" max="100" step="0.01" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                            </div> --}}
                            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Призначити</button>
                        </form>
                    </details>
                @endif
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="mb-4 text-lg font-bold text-gray-900">Персональні знижки</h3>

                @if($viewModel->hasActiveDiscounts())
                    <div class="mb-4 space-y-2">
                        @foreach($viewModel->activeDiscounts() as $discount)
                            <div class="flex items-center justify-between rounded-lg border border-green-200 bg-green-50 p-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $discount->course->name }}</p>
                                    <p class="text-sm text-green-700">Знижка: {{ $discount->formattedValue() }}</p>
                                    <p class="text-xs text-gray-500">Призначив: {{ $discount->assignedBy->name }} · {{ $discount->created_at->format('d.m.Y') }}</p>
                                </div>
                                <form action="{{ route('admin.students.remove-discount', [$student, $discount]) }}" method="POST" onsubmit="return confirm('Видалити знижку?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-error-600 hover:text-error-700">Видалити</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mb-4 text-sm text-gray-500">Немає активних знижок</p>
                @endif

                @if($viewModel->coursesWithoutActiveDiscount()->isNotEmpty())
                    <details>
                        <summary class="cursor-pointer text-sm font-medium text-brand-600">Призначити знижку</summary>
                        <form action="{{ route('admin.students.assign-discount', $student) }}" method="POST" class="mt-4 space-y-4 rounded-lg bg-gray-50 p-4">
                            @csrf
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Курс</label>
                                <select name="course_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2">
                                    @foreach($viewModel->coursesWithoutActiveDiscount() as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }} ({{ number_format($course->price, 2) }} ₴)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Тип знижки</label>
                                    <select name="type" required class="w-full rounded-lg border border-gray-300 px-4 py-2">
                                        <option value="percentage">Відсотки (%)</option>
                                        <option value="fixed">Фіксована сума (грн)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Значення</label>
                                    <input type="number" name="value" min="0.01" step="0.01" required class="w-full rounded-lg border border-gray-300 px-4 py-2" placeholder="10">
                                </div>
                            </div>
                            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Призначити знижку</button>
                        </form>
                    </details>
                @endif

                @if($viewModel->hasUsedDiscounts())
                    <details class="mt-4">
                        <summary class="cursor-pointer text-sm text-gray-500">Історія використаних знижок</summary>
                        <div class="mt-2 space-y-2">
                            @foreach($viewModel->usedDiscounts() as $discount)
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 opacity-60">
                                    <p class="font-medium text-gray-900">{{ $discount->course->name }}</p>
                                    <p class="text-sm text-gray-600">Знижка: {{ $discount->formattedValue() }}</p>
                                    <p class="text-xs text-gray-500">Використано: {{ $discount->used_at->format('d.m.Y H:i') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endif
            </div>

            <livewire:admin.student-transactions :studentId="$student->id" />

            <livewire:admin.student-login-history :studentId="$student->id" />
        </div>
    </div>
</div>
@endsection
