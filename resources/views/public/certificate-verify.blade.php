<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Перевірка сертифіката - LifeScanEducation</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">LifeScanEducation</h1>
            <p class="text-gray-600 mt-2">Перевірка сертифіката</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            @if($certificate)
                @if($certificate->isRevoked())
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-red-600 mb-2">Сертифікат відкликано</h2>
                        <p class="text-gray-600">Цей сертифікат був відкликаний і більше не є дійсним.</p>
                    </div>
                @else
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-green-600 mb-2">Сертифікат дійсний</h2>
                        <p class="text-gray-600">Цей сертифікат є справжнім та дійсним.</p>
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Номер сертифіката</p>
                        <p class="font-semibold text-gray-900">{{ $certificate->certificate_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Власник</p>
                        <p class="font-semibold text-gray-900">{{ $certificate->student->name }} {{ $certificate->student->surname }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Курс</p>
                        <p class="font-semibold text-gray-900">{{ $certificate->course->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Викладач</p>
                        <p class="font-semibold text-gray-900">{{ $certificate->course->teacher?->full_name ?? 'Не вказано' }}</p>
                    </div>
                    <div class="flex gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Оцінка</p>
                            <p class="font-semibold text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $certificate->grade_level->badgeClasses() }}">
                                    {{ $certificate->grade_level->label() }}
                                </span>
                                <span class="text-gray-600 ml-1">({{ number_format($certificate->grade, 1) }}%)</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Години навчання</p>
                            <p class="font-semibold text-gray-900">{{ $certificate->formatted_study_hours }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Дата видачі</p>
                        <p class="font-semibold text-gray-900">{{ $certificate->formatted_issued_at }}</p>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Сертифікат не знайдено</h2>
                    <p class="text-gray-600">Сертифікат з таким номером не існує в нашій системі.</p>
                </div>
            @endif
        </div>

        <div class="text-center mt-8">
            <p class="text-sm text-gray-500">© {{ date('Y') }} LifeScanEducation. Всі права захищені.</p>
        </div>
    </div>
</body>
</html>
