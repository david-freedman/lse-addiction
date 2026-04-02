@extends('admin.layouts.admin')

@section('title', 'Спеціальності')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">Спеціальності</h1>
    <a href="{{ route('admin.specialties.create') }}" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        Додати спеціальність
    </a>
</div>

@if(session('success'))
    <div class="mb-6 rounded-lg bg-success-50 px-4 py-3 text-sm text-success-700">
        {{ session('success') }}
    </div>
@endif

<div class="rounded-2xl border border-gray-200 bg-white">
    @if($specialties->isEmpty())
        <div class="p-8 text-center text-gray-500">
            Спеціальностей ще немає. <a href="{{ route('admin.specialties.create') }}" class="text-brand-600 hover:underline">Додати першу</a>.
        </div>
    @else
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Назва</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Дата створення</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($specialties as $specialty)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $specialty->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $specialty->created_at->format('d.m.Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.specialties.edit', $specialty) }}" class="text-sm font-medium text-brand-600 hover:text-brand-800">
                                    Редагувати
                                </a>
                                <form action="{{ route('admin.specialties.destroy', $specialty) }}" method="POST" onsubmit="return confirm('Видалити спеціальність?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-error-600 hover:text-error-800">
                                        Видалити
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($specialties->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $specialties->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
