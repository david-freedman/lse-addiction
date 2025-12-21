@props(['course'])

<div data-course-id="{{ $course->id }}" class="bg-white rounded-lg p-4 border border-gray-200 hover:border-teal-300 transition cursor-pointer" onclick="window.location.href='{{ route('student.catalog.show', $course->slug) }}'">
    <div class="flex items-center gap-2 mb-2">
        @if($course->label)
            <span class="px-2 py-0.5 text-xs font-medium bg-teal-100 text-teal-700 rounded">{{ $course->label }}</span>
        @endif
    </div>

    <h4 class="text-sm font-semibold text-gray-900 mb-3 line-clamp-2">{{ $course->name }}</h4>

    <div class="flex items-center gap-2 mb-3">
        @if($course->teacherPhotoUrl)
            <img src="{{ $course->teacherPhotoUrl }}" alt="{{ $course->teacherName }}" class="w-8 h-8 rounded-full object-cover">
        @else
            <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xs font-medium">
                {{ mb_substr($course->teacherName, 0, 1) }}
            </div>
        @endif
        <span class="text-xs text-gray-600">{{ $course->teacherName }}</span>
    </div>

    <div class="flex items-center gap-4 text-xs text-gray-500">
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ $course->formattedDate }}
        </span>
    </div>
</div>
