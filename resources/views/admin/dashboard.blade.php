@extends('admin.layouts.admin')

@section('title', 'Панель керування')

@section('content')
{{-- Stats Grid --}}
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
    <x-admin.stat-card
        title="Всього курсів"
        :value="$totalCourses"
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
        title="Опубліковано"
        :value="$publishedCourses"
        iconColor="success"
    >
        <x-slot:icon>
            <svg class="fill-success-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8.43994 12.0002L10.8139 14.3732L15.5599 9.6272" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </x-slot:icon>
    </x-admin.stat-card>

    <x-admin.stat-card
        title="Студентів"
        :value="$totalStudents"
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
        :value="number_format($totalRevenue, 0, ',', ' ') . ' ₴'"
        :change="$revenueChange"
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

{{-- Charts Row --}}
<div class="grid grid-cols-1 gap-4 md:gap-6 xl:grid-cols-2 2xl:gap-7.5 mb-6">
    <x-admin.chart-card title="Дохід за місяцями" chartId="revenueChart" />
    <x-admin.chart-card title="Записи на курси" chartId="enrollmentsChart" />
</div>

{{-- Recent Transactions --}}
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
                @forelse($recentTransactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        #{{ $transaction->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $transaction->student->first_name }} {{ $transaction->student->last_name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $transaction->course->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ number_format($transaction->amount, 0, ',', ' ') }} ₴
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($transaction->status === 'completed')
                            <span class="inline-flex rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-medium text-success-700">
                                Завершено
                            </span>
                        @elseif($transaction->status === 'pending')
                            <span class="inline-flex rounded-full bg-warning-100 px-2.5 py-0.5 text-xs font-medium text-warning-700">
                                Очікується
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-error-100 px-2.5 py-0.5 text-xs font-medium text-error-700">
                                Скасовано
                            </span>
                        @endif
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
    // Revenue Chart
    const revenueData = @json($monthlyRevenue);
    const revenueOptions = {
        series: [{
            name: 'Дохід',
            data: revenueData.map(item => item.total)
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        colors: ['#465fff'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: revenueData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('uk-UA', { month: 'short', year: 'numeric' });
            })
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return val.toLocaleString('uk-UA') + ' ₴';
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val.toLocaleString('uk-UA') + ' ₴';
                }
            }
        }
    };

    const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
    revenueChart.render();

    // Enrollments Chart
    const enrollmentsData = @json($enrollmentsByMonth);
    const enrollmentsOptions = {
        series: [{
            name: 'Записів',
            data: enrollmentsData.map(item => item.total)
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 8,
                dataLabels: {
                    position: 'top',
                },
            }
        },
        dataLabels: {
            enabled: true,
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
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
                formatter: function(val) {
                    return Math.round(val);
                }
            }
        }
    };

    const enrollmentsChart = new ApexCharts(document.querySelector("#enrollmentsChart"), enrollmentsOptions);
    enrollmentsChart.render();
});
</script>
@endpush
@endsection
