@extends('admin.layouts.admin')

@section('title', 'Фінанси')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">Фінанси</h1>
        <p class="mt-1 text-sm text-gray-500">Аналітика платежів та фінансові показники</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex rounded-lg border border-gray-200 bg-white p-1">
            @foreach($statsViewModel->periods() as $periodOption)
                <a href="{{ route('admin.finances.index', array_merge(request()->except('period'), ['period' => $periodOption['value']])) }}"
                   @class([
                       'px-3 py-1.5 text-sm font-medium rounded-md transition',
                       'bg-brand-500 text-white' => $statsViewModel->period() === $periodOption['value'],
                       'text-gray-600 hover:bg-gray-100' => $statsViewModel->period() !== $periodOption['value'],
                   ])>
                    {{ $periodOption['label'] }}
                </a>
            @endforeach
        </div>
        <a href="{{ route('admin.finances.transactions') }}" class="flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Всі транзакції
        </a>
    </div>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-gray-500">Загальний дохід</p>
            @if($statsViewModel->totalRevenueChange() !== null)
                <span @class([
                    'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                    'bg-green-100 text-green-700' => $statsViewModel->totalRevenueChange() >= 0,
                    'bg-red-100 text-red-700' => $statsViewModel->totalRevenueChange() < 0,
                ])>
                    {{ $statsViewModel->totalRevenueChange() >= 0 ? '↑' : '↓' }}
                    {{ abs($statsViewModel->totalRevenueChange()) }}%
                </span>
            @endif
        </div>
        <p class="mt-2 text-2xl font-bold text-success-600">{{ $statsViewModel->formatAmount($statsViewModel->totalRevenue()) }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-gray-500">Транзакцій</p>
            @if($statsViewModel->totalTransactionsChange() !== null)
                <span @class([
                    'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                    'bg-green-100 text-green-700' => $statsViewModel->totalTransactionsChange() >= 0,
                    'bg-red-100 text-red-700' => $statsViewModel->totalTransactionsChange() < 0,
                ])>
                    {{ $statsViewModel->totalTransactionsChange() >= 0 ? '↑' : '↓' }}
                    {{ abs($statsViewModel->totalTransactionsChange()) }}%
                </span>
            @endif
        </div>
        <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($statsViewModel->totalTransactions(), 0, ',', ' ') }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-gray-500">Успішних транзакцій</p>
            @if($statsViewModel->successRateChange() !== null)
                <span @class([
                    'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                    'bg-green-100 text-green-700' => $statsViewModel->successRateChange() >= 0,
                    'bg-red-100 text-red-700' => $statsViewModel->successRateChange() < 0,
                ])>
                    {{ $statsViewModel->successRateChange() >= 0 ? '↑' : '↓' }}
                    {{ abs($statsViewModel->successRateChange()) }}%
                </span>
            @endif
        </div>
        <p class="mt-2 text-2xl font-bold text-gray-900">{{ $statsViewModel->successRate() }}%</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1">
                <p class="text-sm font-medium text-gray-500">Конверсія</p>
                <x-admin.tooltip text="Студенти з покупками / Всього студентів × 100%">
                    <svg class="h-4 w-4 cursor-help text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-admin.tooltip>
            </div>
            @if($statsViewModel->purchaseConversionRateChange() !== null)
                <span @class([
                    'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                    'bg-green-100 text-green-700' => $statsViewModel->purchaseConversionRateChange() >= 0,
                    'bg-red-100 text-red-700' => $statsViewModel->purchaseConversionRateChange() < 0,
                ])>
                    {{ $statsViewModel->purchaseConversionRateChange() >= 0 ? '↑' : '↓' }}
                    {{ abs($statsViewModel->purchaseConversionRateChange()) }}%
                </span>
            @endif
        </div>
        <p class="mt-2 text-2xl font-bold text-gray-900">{{ $statsViewModel->purchaseConversionRate() }}%</p>
    </div>
</div>

<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6">
    <h3 class="mb-4 text-lg font-semibold text-gray-900">Динаміка доходу</h3>
    <div id="dailyRevenueChart"></div>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <p class="text-sm font-medium text-gray-500">Дохід за сьогодні</p>
        <p class="mt-2 text-xl font-bold text-success-600">{{ $statsViewModel->formatAmount($statsViewModel->todayRevenue()) }}</p>
        <p class="mt-1 text-xs text-gray-400">Успішні транзакції за сьогодні</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <p class="text-sm font-medium text-gray-500">Дохід за тиждень</p>
        <p class="mt-2 text-xl font-bold text-brand-600">{{ $statsViewModel->formatAmount($statsViewModel->weekRevenue()) }}</p>
        <p class="mt-1 text-xs text-gray-400">Успішні транзакції за 7 днів</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <p class="text-sm font-medium text-gray-500">Нові студенти</p>
        <p class="mt-2 text-xl font-bold text-warning-600">{{ number_format($statsViewModel->newStudentsCount(), 0, ',', ' ') }}</p>
        <p class="mt-1 text-xs text-gray-400">За обраний період</p>
    </div>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="flex items-center gap-1">
            <p class="text-sm font-medium text-gray-500">Дохід на студента</p>
            <x-admin.tooltip text="Загальний дохід / Кількість студентів з покупками">
                <svg class="h-4 w-4 cursor-help text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-admin.tooltip>
        </div>
        <p class="mt-2 text-xl font-bold text-brand-600">{{ $statsViewModel->formatAmount($statsViewModel->revenuePerStudent()) }}</p>
        <p class="mt-1 text-xs text-gray-400">LTV за обраний період</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="flex items-center gap-1">
            <p class="text-sm font-medium text-gray-500">Дохід на курс</p>
            <x-admin.tooltip text="Загальний дохід / Кількість проданих курсів">
                <svg class="h-4 w-4 cursor-help text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-admin.tooltip>
        </div>
        <p class="mt-2 text-xl font-bold text-success-600">{{ $statsViewModel->formatAmount($statsViewModel->revenuePerCourse()) }}</p>
        <p class="mt-1 text-xs text-gray-400">Середній дохід з курсу</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="flex items-center gap-1">
            <p class="text-sm font-medium text-gray-500">Повторні покупки</p>
            <x-admin.tooltip text="Студенти з 2+ покупками / Всього студентів × 100%">
                <svg class="h-4 w-4 cursor-help text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-admin.tooltip>
        </div>
        <p class="mt-2 text-xl font-bold text-warning-600">{{ $statsViewModel->repeatBuyersPercentage() }}%</p>
        <p class="mt-1 text-xs text-gray-400">Студенти з 2+ покупками</p>
    </div>
</div>

<div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Топ курсів за доходом</h3>
        @if(count($statsViewModel->topCourses(3)) > 0)
            <div class="space-y-3">
                @foreach($statsViewModel->topCourses(3) as $index => $course)
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                        <div class="flex items-center gap-3">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-600">{{ $index + 1 }}</span>
                            <span class="font-medium text-gray-900">{{ $course['name'] }}</span>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ $statsViewModel->formatAmount($course['revenue']) }}</p>
                            <p class="text-xs text-gray-500">{{ $course['count'] }} продажів · {{ $course['percentage'] }}%</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="py-8 text-center text-gray-500">Немає даних для відображення</p>
        @endif
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Способи оплати</h3>
        @if(count($statsViewModel->paymentMethodsDistribution()) > 0)
            <div class="space-y-3">
                @foreach($statsViewModel->paymentMethodsDistribution() as $method)
                    <div class="flex items-center gap-4">
                        <div class="w-24">
                            <span class="text-sm font-medium text-gray-700">{{ $method['label'] }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="h-3 overflow-hidden rounded-full bg-gray-100">
                                <div class="h-full rounded-full bg-brand-500 transition-all" style="width: {{ $method['percentage'] }}%"></div>
                            </div>
                        </div>
                        <div class="w-20 text-right">
                            <span class="font-medium text-gray-900">{{ $method['count'] }}</span>
                            <span class="text-sm text-gray-500">({{ $method['percentage'] }}%)</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="py-8 text-center text-gray-500">Немає даних для відображення</p>
        @endif
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-5">
    <h3 class="mb-4 text-sm font-semibold text-gray-700">Розподіл за статусами</h3>
    <div class="space-y-3">
        @foreach($statsViewModel->byStatus() as $key => $stat)
            <div class="flex items-center gap-4">
                <div class="w-24">
                    <div class="flex items-center gap-2">
                        <span @class([
                            'inline-block h-2 w-2 rounded-full',
                            'bg-gray-400' => $stat['color'] === 'gray',
                            'bg-orange-400' => $stat['color'] === 'orange',
                            'bg-green-400' => $stat['color'] === 'green',
                            'bg-red-400' => $stat['color'] === 'red',
                        ])></span>
                        <span class="text-sm text-gray-600">{{ $stat['label'] }}</span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                        <div @class([
                            'h-full rounded-full transition-all',
                            'bg-gray-400' => $stat['color'] === 'gray',
                            'bg-orange-400' => $stat['color'] === 'orange',
                            'bg-green-400' => $stat['color'] === 'green',
                            'bg-red-400' => $stat['color'] === 'red',
                        ]) style="width: {{ $stat['percentage'] }}%"></div>
                    </div>
                </div>
                <div class="w-32 text-right">
                    <span class="font-medium text-gray-900">{{ $stat['count'] }}</span>
                    <span class="text-sm text-gray-500">({{ $statsViewModel->formatAmount($stat['amount']) }})</span>
                </div>
                <div class="w-16 text-right text-sm text-gray-500">
                    {{ $stat['percentage'] }}%
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dailyData = @json($statsViewModel->getDailyRevenue());

        if (dailyData.values.some(v => v > 0)) {
            new ApexCharts(document.getElementById('dailyRevenueChart'), {
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: {
                        show: true,
                        tools: {
                            download: false,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true
                        }
                    },
                    zoom: {
                        enabled: true
                    },
                    fontFamily: 'inherit'
                },
                series: [{
                    name: 'Дохід',
                    data: dailyData.values
                }],
                xaxis: {
                    categories: dailyData.labels,
                    labels: {
                        rotate: -45,
                        hideOverlappingLabels: true
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return val.toLocaleString('uk-UA') + ' ₴';
                        }
                    }
                },
                colors: ['#465fff'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                    }
                },
                stroke: { curve: 'smooth', width: 2 },
                dataLabels: { enabled: false },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toLocaleString('uk-UA') + ' ₴';
                        }
                    }
                }
            }).render();
        } else {
            document.getElementById('dailyRevenueChart').innerHTML = '<p class="py-12 text-center text-gray-500">Немає даних для відображення</p>';
        }
    });
</script>
@endpush
