@extends('admin.layouts.admin')

@section('title', $viewModel->group()->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">{{ $viewModel->group()->name }}</h1>
        <p class="mt-1 text-sm text-gray-500">Студентів: {{ $viewModel->studentsCount() }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.student-groups.edit', $studentGroup) }}" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
            Редагувати
        </a>
        <a href="{{ route('admin.student-groups.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
            До списку
        </a>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-1">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="mb-4 text-lg font-bold text-gray-900">Інформація</h3>

            <div class="space-y-3">
                <div>
                    <p class="text-xs font-medium text-gray-500">Назва</p>
                    <p class="text-sm text-gray-900">{{ $viewModel->group()->name }}</p>
                </div>

                @if($viewModel->group()->description)
                    <div>
                        <p class="text-xs font-medium text-gray-500">Опис</p>
                        <p class="text-sm text-gray-900">{{ $viewModel->group()->description }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-xs font-medium text-gray-500">Курс</p>
                    @if($viewModel->course())
                        <a href="{{ route('admin.courses.show', $viewModel->course()) }}" class="text-sm text-brand-600 hover:text-brand-700">
                            {{ $viewModel->course()->name }}
                        </a>
                    @else
                        <p class="text-sm text-gray-400">Без курсу</p>
                    @endif
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500">Створив</p>
                    @if($viewModel->creator())
                        <p class="text-sm text-gray-900">{{ $viewModel->creator()->name }}</p>
                    @else
                        <p class="text-sm text-gray-400">—</p>
                    @endif
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500">Дата створення</p>
                    <p class="text-sm text-gray-900">{{ $viewModel->group()->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>

            <form action="{{ route('admin.student-groups.destroy', $studentGroup) }}" method="POST" class="mt-6" onsubmit="return confirm('Видалити групу?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded-lg bg-error-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-error-600">
                    Видалити групу
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="space-y-6">
            @if($viewModel->hasAvailableStudents())
                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <h3 class="mb-4 text-lg font-bold text-gray-900">Додати студентів</h3>
                    <form action="{{ route('admin.student-groups.add-students', $studentGroup) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <select name="student_ids[]" multiple class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3" size="5">
                                @foreach($viewModel->availableStudents() as $student)
                                    <option value="{{ $student->id }}">{{ $student->surname }} {{ $student->name }} ({{ $student->email->value }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Утримуйте Ctrl для вибору декількох</p>
                        </div>
                        <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                            Додати вибраних
                        </button>
                    </form>
                </div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="mb-4 text-lg font-bold text-gray-900">Учасники групи ({{ $viewModel->studentsCount() }})</h3>

                @if($viewModel->hasStudents())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-gray-200 bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Студент</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Доданий</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600">Дії</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($viewModel->students() as $student)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.students.show', $student) }}" class="font-medium text-gray-900 hover:text-brand-600">
                                                {{ $student->surname }} {{ $student->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-sm text-gray-600">{{ $student->email->value }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-sm text-gray-600">
                                                {{ $student->pivot->added_at ? \Carbon\Carbon::parse($student->pivot->added_at)->format('d.m.Y') : '—' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex justify-end">
                                                <form action="{{ route('admin.student-groups.remove-student', [$studentGroup, $student]) }}" method="POST" onsubmit="return confirm('Видалити студента з групи?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-error-600 hover:text-error-700">
                                                        Видалити
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $viewModel->students()->links() }}
                    </div>
                @else
                    <div class="py-8 text-center">
                        <p class="text-gray-500">У групі ще немає студентів</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
