@props([
    'type',
    'contact',
    'buttonText' => 'Підтвердити',
    'inputClass' => '',
    'buttonColor' => 'teal',
])

<input type="hidden" name="type" value="{{ $type }}">
<input type="hidden" name="contact" value="{{ $contact }}">

<div class="mb-6">
    <label for="code" class="block text-sm font-medium text-gray-700 mb-2 text-center">
        Код підтвердження
    </label>
    <input type="text"
        name="code"
        id="code"
        maxlength="4"
        placeholder="••••"
        class="w-full px-4 py-4 text-center text-3xl font-bold tracking-[1rem] border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $buttonColor }}-500 focus:border-transparent {{ $inputClass }} @error('code') border-red-500 ring-2 ring-red-200 @enderror"
        required
        autofocus
        autocomplete="one-time-code"
        inputmode="numeric"
        pattern="[0-9]*">
    @error('code')
        <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
    @enderror
</div>

<button type="submit"
    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-{{ $buttonColor }}-500 text-white font-medium rounded-lg hover:bg-{{ $buttonColor }}-600 transition focus:outline-none focus:ring-2 focus:ring-{{ $buttonColor }}-500 focus:ring-offset-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    {{ $buttonText }}
</button>
