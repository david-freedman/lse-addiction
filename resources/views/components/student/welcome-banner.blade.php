@props(['greeting', 'message'])

<div class="bg-gradient-to-r from-teal-500 to-teal-400 rounded-2xl p-6 text-white">
    <h1 class="text-2xl font-bold mb-2">{{ $greeting }} <span class="inline-block">&#128075;</span></h1>
    <p class="text-teal-50">{{ $message }}</p>
</div>
