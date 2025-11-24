<input type="hidden" name="type" value="{{ $type }}">
<input type="hidden" name="contact" value="{{ $contact }}">

<div class="mb-6">
    <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Код підтвердження</label>
    <input type="text" name="code" id="code" maxlength="4"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-center text-2xl tracking-widest leading-tight focus:outline-none focus:shadow-outline @error('code') border-red-500 @enderror"
        required autofocus>
    @error('code')
        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
    @enderror
</div>

<button type="submit"
    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
    {{ $buttonText }}
</button>
