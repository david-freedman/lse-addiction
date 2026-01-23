@extends('admin.layouts.admin')

@section('title', 'Панель керування')

@section('content')
@if($viewModel->isAdmin())
    {{-- Admin Dashboard --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
        <x-admin.stat-card
            title="Всього курсів"
            :value="$viewModel->totalCourses()"
            iconColor="brand"
        >
            <x-slot:icon>
                <svg class="fill-brand-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card
            title="Викладачів"
            :value="$viewModel->totalTeachers()"
            iconColor="success"
        >
            <x-slot:icon>
                <svg class="fill-success-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M20.5899 22C20.5899 18.13 16.7399 15 11.9999 15C7.25991 15 3.40991 18.13 3.40991 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card
            title="Студентів"
            :value="$viewModel->totalStudents()"
            iconColor="warning"
        >
            <x-slot:icon>
                <svg class="fill-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.59151 15.2068C13.2805 15.2068 16.4335 15.7658 16.4335 17.9988C16.4335 20.2318 13.3015 20.8068 9.59151 20.8068C5.90151 20.8068 2.74951 20.2528 2.74951 18.0188C2.74951 15.7848 5.88051 15.2068 9.59151 15.2068Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.59157 12.0198C7.16957 12.0198 5.20557 10.0568 5.20557 7.63476C5.20557 5.21276 7.16957 3.24976 9.59157 3.24976C12.0126 3.24976 13.9766 5.21276 13.9766 7.63476C13.9856 10.0478 12.0356 12.0108 9.62257 12.0198H9.59157Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16.4829 10.8821C18.0839 10.6561 19.3169 9.28207 19.3199 7.61907C19.3199 5.98007 18.1249 4.62007 16.5579 4.36107" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.5952 14.7322C20.1462 14.9632 21.2292 15.5072 21.2292 16.6272C21.2292 17.3982 20.7192 17.8982 19.8952 18.2112" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card
            title="Дохід"
            :value="number_format($viewModel->totalRevenue(), 0, ',', ' ') . ' ₴'"
            :change="$viewModel->revenueChange()"
            iconColor="brand"
        >
            <x-slot:icon>
                <svg class="fill-brand-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 1V23" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </x-slot:icon>
        </x-admin.stat-card>
    </div>

    <div class="grid grid-cols-1 gap-4 md:gap-6 xl:grid-cols-2 2xl:gap-7.5 mb-6">
        <x-admin.chart-card title="Дохід за місяцями" chartId="revenueChart" />
        <x-admin.chart-card title="Записи на курси" chartId="enrollmentsChart" />
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Останні транзакції</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Студент</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Курс</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Сума</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($viewModel->recentTransactions() as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            #{{ $transaction->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->student->name }} {{ $transaction->student->surname }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $transaction->course->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($transaction->amount, 0, ',', ' ') }} ₴
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = match($transaction->status) {
                                    \App\Domains\Transaction\Enums\TransactionStatus::Pending => 'bg-gray-100 text-gray-700',
                                    \App\Domains\Transaction\Enums\TransactionStatus::Processing => 'bg-orange-100 text-orange-700',
                                    \App\Domains\Transaction\Enums\TransactionStatus::Completed => 'bg-green-100 text-green-700',
                                    \App\Domains\Transaction\Enums\TransactionStatus::Failed => 'bg-red-100 text-red-700',
                                };
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClasses }}">
                                {{ $transaction->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->format('d.m.Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                            Транзакції відсутні
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const revenueData = @json($viewModel->monthlyRevenue());
        const revenueOptions = {
            series: [{
                name: 'Дохід',
                data: revenueData.map(item => item.total)
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            colors: ['#465fff'],
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] }
            },
            xaxis: {
                categories: revenueData.map(item => {
                    const date = new Date(item.month + '-01');
                    return date.toLocaleDateString('uk-UA', { month: 'short', year: 'numeric' });
                })
            },
            yaxis: {
                labels: {
                    formatter: function(val) { return val.toLocaleString('uk-UA') + ' ₴'; }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) { return val.toLocaleString('uk-UA') + ' ₴'; }
                }
            }
        };
        new ApexCharts(document.querySelector("#revenueChart"), revenueOptions).render();

        const enrollmentsData = @json($viewModel->enrollmentsByMonth());
        const enrollmentsOptions = {
            series: [{
                name: 'Записів',
                data: enrollmentsData.map(item => item.total)
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: { borderRadius: 8, dataLabels: { position: 'top' } }
            },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: { fontSize: '12px', colors: ["#304758"] }
            },
            colors: ['#12b76a'],
            xaxis: {
                categories: enrollmentsData.map(item => {
                    const date = new Date(item.month + '-01');
                    return date.toLocaleDateString('uk-UA', { month: 'short', year: 'numeric' });
                })
            },
            yaxis: {
                labels: {
                    formatter: function(val) { return Math.round(val); }
                }
            }
        };
        new ApexCharts(document.querySelector("#enrollmentsChart"), enrollmentsOptions).render();
    });
    </script>
    @endpush

@else
    {{-- Teacher Dashboard --}}
    @if($viewModel->hasNoCourses())
        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Курси не знайдено</h3>
            <p class="mt-2 text-sm text-gray-500">Вам ще не призначено жодного курсу. Зверніться до адміністратора.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
            <x-admin.stat-card
                title="Мої курси"
                :value="$viewModel->myCourseCount()"
                iconColor="brand"
            >
                <x-slot:icon>
                    <svg class="fill-brand-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot:icon>
            </x-admin.stat-card>

            <x-admin.stat-card
                title="Мої студенти"
                :value="$viewModel->myStudentCount()"
                iconColor="success"
            >
                <x-slot:icon>
                    <svg class="fill-success-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.59151 15.2068C13.2805 15.2068 16.4335 15.7658 16.4335 17.9988C16.4335 20.2318 13.3015 20.8068 9.59151 20.8068C5.90151 20.8068 2.74951 20.2528 2.74951 18.0188C2.74951 15.7848 5.88051 15.2068 9.59151 15.2068Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.59157 12.0198C7.16957 12.0198 5.20557 10.0568 5.20557 7.63476C5.20557 5.21276 7.16957 3.24976 9.59157 3.24976C12.0126 3.24976 13.9766 5.21276 13.9766 7.63476C13.9856 10.0478 12.0356 12.0108 9.62257 12.0198H9.59157Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16.4829 10.8821C18.0839 10.6561 19.3169 9.28207 19.3199 7.61907C19.3199 5.98007 18.1249 4.62007 16.5579 4.36107" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18.5952 14.7322C20.1462 14.9632 21.2292 15.5072 21.2292 16.6272C21.2292 17.3982 20.7192 17.8982 19.8952 18.2112" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot:icon>
            </x-admin.stat-card>

            <x-admin.stat-card
                title="Середній прогрес"
                :value="$viewModel->averageProgress() . '%'"
                iconColor="info"
            >
                <x-slot:icon>
                    <svg class="fill-info-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot:icon>
            </x-admin.stat-card>

            <x-admin.stat-card
                title="Очікують перевірки"
                :value="$viewModel->pendingHomeworkCount()"
                iconColor="warning"
            >
                <x-slot:icon>
                    <svg class="fill-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot:icon>
            </x-admin.stat-card>
        </div>

        <div class="grid grid-cols-1 gap-4 md:gap-6 xl:grid-cols-2 2xl:gap-7.5 mb-6">
            <x-admin.chart-card title="Записи на мої курси" chartId="teacherEnrollmentsChart" />
            <x-admin.chart-card title="Тренд прогресу студентів" chartId="progressTrendChart" />
        </div>

        <div class="grid grid-cols-1 gap-4 md:gap-6 xl:grid-cols-2 2xl:gap-7.5">
            <div class="rounded-2xl border border-gray-200 bg-white">
                <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Очікують перевірки</h3>
                    @if($viewModel->pendingHomeworkCount() > 0)
                        <a href="{{ route('admin.homework.index') }}" class="text-sm text-brand-600 hover:text-brand-700">
                            Переглянути всі
                        </a>
                    @endif
                </div>
                <div class="p-6">
                    @forelse($viewModel->pendingHomeworkList() as $submission)
                        <div class="flex items-start gap-4 {{ !$loop->last ? 'mb-4 pb-4 border-b border-gray-100' : '' }}">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-warning-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-warning-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $submission->student->name }} {{ $submission->student->surname }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $submission->homework->lesson->module->course->name }} — {{ $submission->homework->name }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $submission->submitted_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('admin.homework.submissions.show', $submission) }}" class="flex-shrink-0 text-brand-600 hover:text-brand-700">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Немає завдань для перевірки</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white">
                <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Майбутні вебінари</h3>
                    @if($viewModel->upcomingWebinars()->isNotEmpty())
                        <a href="{{ route('admin.webinars.index') }}" class="text-sm text-brand-600 hover:text-brand-700">
                            Переглянути всі
                        </a>
                    @endif
                </div>
                <div class="p-6">
                    @forelse($viewModel->upcomingWebinars() as $webinar)
                        <div class="flex items-start gap-4 {{ !$loop->last ? 'mb-4 pb-4 border-b border-gray-100' : '' }}">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $webinar->title }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $webinar->starts_at->translatedFormat('j F, H:i') }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $webinar->participantsCount() }} учасників
                                </p>
                            </div>
                            <a href="{{ route('admin.webinars.show', $webinar) }}" class="flex-shrink-0 text-brand-600 hover:text-brand-700">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Немає запланованих вебінарів</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const enrollmentsData = @json($viewModel->myEnrollmentsByMonth());
            if (enrollmentsData.length > 0) {
                const enrollmentsOptions = {
                    series: [{
                        name: 'Записів',
                        data: enrollmentsData.map(item => item.total)
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: { borderRadius: 8, dataLabels: { position: 'top' } }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetY: -20,
                        style: { fontSize: '12px', colors: ["#304758"] }
                    },
                    colors: ['#465fff'],
                    xaxis: {
                        categories: enrollmentsData.map(item => {
                            const date = new Date(item.month + '-01');
                            return date.toLocaleDateString('uk-UA', { month: 'short', year: 'numeric' });
                        })
                    },
                    yaxis: {
                        labels: {
                            formatter: function(val) { return Math.round(val); }
                        }
                    }
                };
                new ApexCharts(document.querySelector("#teacherEnrollmentsChart"), enrollmentsOptions).render();
            }

            const progressData = @json($viewModel->progressTrendByMonth());
            if (progressData.length > 0) {
                const progressOptions = {
                    series: [{
                        name: 'Середній прогрес',
                        data: progressData.map(item => item.average)
                    }],
                    chart: {
                        type: 'area',
                        height: 350,
                        toolbar: { show: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    colors: ['#12b76a'],
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] }
                    },
                    xaxis: {
                        categories: progressData.map(item => {
                            const date = new Date(item.month + '-01');
                            return date.toLocaleDateString('uk-UA', { month: 'short', year: 'numeric' });
                        })
                    },
                    yaxis: {
                        min: 0,
                        max: 100,
                        labels: {
                            formatter: function(val) { return val.toFixed(0) + '%'; }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) { return val.toFixed(1) + '%'; }
                        }
                    }
                };
                new ApexCharts(document.querySelector("#progressTrendChart"), progressOptions).render();
            }
        });
        </script>
        @endpush
    @endif
@endif
@endsection
