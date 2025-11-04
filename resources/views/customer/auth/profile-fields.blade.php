@extends('layouts.app')

@section('title', 'Додаткова інформація')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-2 text-gray-900">Додаткова інформація</h2>
        <p class="text-gray-600 mb-6">Заповніть, будь ласка, додаткову інформацію</p>

        <form action="{{ route('customer.profile-fields.save') }}" method="POST">
            @csrf

            @foreach($fields as $field)
                <div class="mb-4">
                    <label for="field_{{ $field->key }}" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ $field->label }}
                        @if($field->is_required)
                            <span class="text-red-500">*</span>
                        @endif
                    </label>

                    @switch($field->type->value)
                        @case('text')
                            <input type="text"
                                name="profile_fields[{{ $field->key }}]"
                                id="field_{{ $field->key }}"
                                value="{{ old('profile_fields.' . $field->key) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                {{ $field->is_required ? 'required' : '' }}>
                            @break

                        @case('number')
                            <input type="number"
                                name="profile_fields[{{ $field->key }}]"
                                id="field_{{ $field->key }}"
                                value="{{ old('profile_fields.' . $field->key) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                {{ $field->is_required ? 'required' : '' }}>
                            @break

                        @case('date')
                            <input type="date"
                                name="profile_fields[{{ $field->key }}]"
                                id="field_{{ $field->key }}"
                                value="{{ old('profile_fields.' . $field->key) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                {{ $field->is_required ? 'required' : '' }}>
                            @break

                        @case('select')
                            <select name="profile_fields[{{ $field->key }}]"
                                id="field_{{ $field->key }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                {{ $field->is_required ? 'required' : '' }}>
                                <option value="">Оберіть варіант</option>
                                @if($field->options)
                                    @foreach($field->options as $optionKey => $optionLabel)
                                        <option value="{{ $optionKey }}" {{ old('profile_fields.' . $field->key) == $optionKey ? 'selected' : '' }}>
                                            {{ $optionLabel }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @break
                    @endswitch

                    @error('profile_fields.' . $field->key)
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="flex items-center justify-between mt-6">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Зберегти
                </button>
                <form action="{{ route('customer.profile-fields.skip') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="text-sm text-gray-600 hover:text-gray-900">
                        Пропустити
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
