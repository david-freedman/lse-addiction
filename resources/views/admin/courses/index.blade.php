@extends('layouts.app')

@section('title', 'Курси - Адміністрування')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Курси</h1>
        <a href="{{ route('admin.courses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Створити курс
        </a>
    </div>

    @if($viewModel->hasNoCourses())
        <div class="bg-white shadow-md rounded-lg px-8 py-12 text-center">
            <p class="text-gray-600">Курсів ще немає.</p>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Назва</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Коуч</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ціна</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Теги</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Дії</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($viewModel->courses() as $course)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.courses.show', $course) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                    {{ $course->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $course->coach->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ number_format($course->price, 2, ',', ' ') }} грн
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColor = match($course->status->color()) {
                                        'green' => 'bg-green-100 text-green-800',
                                        'gray' => 'bg-gray-100 text-gray-800',
                                        'blue' => 'bg-blue-100 text-blue-800',
                                        'purple' => 'bg-purple-100 text-purple-800',
                                        'orange' => 'bg-orange-100 text-orange-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 py-1 {{ $statusColor }} text-xs rounded">{{ $course->status->label() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($course->tags as $tag)
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.courses.edit', $course) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Редагувати</a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Ви впевнені, що хочете видалити цей курс?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Видалити</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $viewModel->courses()->links() }}
        </div>
    @endif
</div>
@endsection
