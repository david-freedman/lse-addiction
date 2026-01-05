<?php

namespace App\Domains\Transaction\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Data\TransactionFilterData;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

readonly class AdminTransactionStatsViewModel
{
    private TransactionFilterData $filters;

    private string $period;

    private ?Carbon $dateFrom;

    private ?Carbon $dateTo;

    private ?Carbon $prevDateFrom;

    private ?Carbon $prevDateTo;

    public function __construct(TransactionFilterData $filters, string $period = 'all')
    {
        $this->filters = $filters;
        $this->period = $period;
        [$this->dateFrom, $this->dateTo] = $this->getPeriodDates($period);
        [$this->prevDateFrom, $this->prevDateTo] = $this->getPreviousPeriodDates($period);
    }

    /** @return array{Carbon|null, Carbon|null} */
    private function getPeriodDates(string $period): array
    {
        return match ($period) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'quarter' => [now()->startOfQuarter(), now()->endOfQuarter()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            default => [null, null],
        };
    }

    /** @return array{Carbon|null, Carbon|null} */
    private function getPreviousPeriodDates(string $period): array
    {
        return match ($period) {
            'today' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            'week' => [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()],
            'month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'quarter' => [now()->subQuarter()->startOfQuarter(), now()->subQuarter()->endOfQuarter()],
            'year' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            default => [null, null],
        };
    }

    public function period(): string
    {
        return $this->period;
    }

    /** @return array<array{value: string, label: string}> */
    public function periods(): array
    {
        return [
            ['value' => 'today', 'label' => 'Сьогодні'],
            ['value' => 'week', 'label' => 'Тиждень'],
            ['value' => 'month', 'label' => 'Місяць'],
            ['value' => 'quarter', 'label' => 'Квартал'],
            ['value' => 'year', 'label' => 'Рік'],
            ['value' => 'all', 'label' => 'Весь час'],
        ];
    }

    public function totalTransactions(): int
    {
        return $this->applyPeriodFilter(Transaction::query())->count();
    }

    public function totalTransactionsChange(): ?float
    {
        if ($this->period === 'all') {
            return null;
        }

        $previous = $this->applyPreviousPeriodFilter(Transaction::query())->count();

        return $this->calculateChange($this->totalTransactions(), $previous);
    }

    public function totalRevenue(): float
    {
        return (float) $this->applyPeriodFilter(Transaction::completed())->sum('amount');
    }

    public function totalRevenueChange(): ?float
    {
        if ($this->period === 'all') {
            return null;
        }

        $previous = (float) $this->applyPreviousPeriodFilter(Transaction::completed())->sum('amount');

        return $this->calculateChange($this->totalRevenue(), $previous);
    }

    public function successRate(): float
    {
        $completed = $this->applyPeriodFilter(Transaction::completed())->count();
        $failed = $this->applyPeriodFilter(Transaction::query()->where('status', TransactionStatus::Failed->value))->count();

        $total = $completed + $failed;
        if ($total === 0) {
            return 0;
        }

        return round(($completed / $total) * 100, 1);
    }

    public function successRateChange(): ?float
    {
        if ($this->period === 'all') {
            return null;
        }

        $prevCompleted = $this->applyPreviousPeriodFilter(Transaction::completed())->count();
        $prevFailed = $this->applyPreviousPeriodFilter(Transaction::query()->where('status', TransactionStatus::Failed->value))->count();

        $prevTotal = $prevCompleted + $prevFailed;
        $prevRate = $prevTotal > 0 ? ($prevCompleted / $prevTotal) * 100 : 0;

        return $this->calculateChange($this->successRate(), $prevRate);
    }

    public function purchaseConversionRate(): float
    {
        $totalStudents = Student::query()
            ->when($this->dateFrom && $this->dateTo, fn ($q) => $q->whereBetween('created_at', [$this->dateFrom, $this->dateTo]))
            ->count();

        if ($totalStudents === 0) {
            return 0;
        }

        $studentsWithPurchases = $this->applyPeriodFilter(Transaction::completed())
            ->distinct('student_id')
            ->count('student_id');

        return round(($studentsWithPurchases / $totalStudents) * 100, 1);
    }

    public function purchaseConversionRateChange(): ?float
    {
        if ($this->period === 'all') {
            return null;
        }

        $prevStudents = Student::query()
            ->when($this->prevDateFrom && $this->prevDateTo, fn ($q) => $q->whereBetween('created_at', [$this->prevDateFrom, $this->prevDateTo]))
            ->count();

        $prevBuyers = $this->applyPreviousPeriodFilter(Transaction::completed())
            ->distinct('student_id')
            ->count('student_id');

        $prevRate = $prevStudents > 0 ? ($prevBuyers / $prevStudents) * 100 : 0;

        return $this->calculateChange($this->purchaseConversionRate(), $prevRate);
    }

    public function totalDiscountAmount(): float
    {
        return (float) $this->applyPeriodFilter(Transaction::completed())
            ->whereNotNull('metadata->discount_amount')
            ->selectRaw("COALESCE(SUM((metadata->>'discount_amount')::numeric), 0) as total")
            ->value('total');
    }

    public function pendingAmount(): float
    {
        return (float) $this->applyPeriodFilter(
            Transaction::query()->where('status', TransactionStatus::Pending->value)
        )->sum('amount');
    }

    public function repeatBuyersCount(): int
    {
        $subQuery = $this->applyPeriodFilter(Transaction::completed())
            ->select('student_id')
            ->selectRaw('COUNT(*) as purchases')
            ->groupBy('student_id')
            ->having(DB::raw('COUNT(*)'), '>', 1);

        return DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery->getQuery())
            ->count();
    }

    public function repeatBuyersPercentage(): float
    {
        $totalBuyers = $this->applyPeriodFilter(Transaction::completed())
            ->distinct('student_id')
            ->count('student_id');

        if ($totalBuyers === 0) {
            return 0;
        }

        return round(($this->repeatBuyersCount() / $totalBuyers) * 100, 1);
    }

    public function revenuePerStudent(): float
    {
        $uniqueBuyers = $this->applyPeriodFilter(Transaction::completed())
            ->distinct('student_id')
            ->count('student_id');

        if ($uniqueBuyers === 0) {
            return 0;
        }

        return round($this->totalRevenue() / $uniqueBuyers, 2);
    }

    public function revenuePerCourse(): float
    {
        $uniqueCourses = $this->applyPeriodFilter(Transaction::completed())
            ->where('purchasable_type', Course::class)
            ->distinct('purchasable_id')
            ->count('purchasable_id');

        if ($uniqueCourses === 0) {
            return 0;
        }

        return round($this->totalRevenue() / $uniqueCourses, 2);
    }

    /** @return array<array{method: string, label: string, count: int, percentage: float}> */
    public function paymentMethodsDistribution(): array
    {
        $total = $this->applyPeriodFilter(Transaction::completed())
            ->whereNotNull('payment_method')
            ->count();

        if ($total === 0) {
            return [];
        }

        $data = $this->applyPeriodFilter(Transaction::completed())
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();

        $result = [];
        foreach ($data as $item) {
            $method = $item->payment_method;
            $result[] = [
                'method' => $item->payment_method,
                'label' => $method?->label() ?? $item->payment_method,
                'count' => (int) $item->count,
                'percentage' => round(($item->count / $total) * 100, 1),
            ];
        }

        return $result;
    }

    private function applyPeriodFilter($query)
    {
        if ($this->dateFrom && $this->dateTo) {
            return $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
        }

        return $query;
    }

    private function applyPreviousPeriodFilter($query)
    {
        if ($this->prevDateFrom && $this->prevDateTo) {
            return $query->whereBetween('created_at', [$this->prevDateFrom, $this->prevDateTo]);
        }

        return $query;
    }

    private function calculateChange(float $current, float $previous): ?float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    public function todayTransactions(): int
    {
        return Transaction::whereDate('created_at', today())->count();
    }

    public function todayRevenue(): float
    {
        return (float) Transaction::completed()
            ->whereDate('created_at', today())
            ->sum('amount');
    }

    public function weekTransactions(): int
    {
        return Transaction::where('created_at', '>=', now()->startOfWeek())->count();
    }

    public function weekRevenue(): float
    {
        return (float) Transaction::completed()
            ->where('created_at', '>=', now()->startOfWeek())
            ->sum('amount');
    }

    public function monthTransactions(): int
    {
        return Transaction::where('created_at', '>=', now()->startOfMonth())->count();
    }

    public function monthRevenue(): float
    {
        return (float) Transaction::completed()
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount');
    }

    public function newStudentsCount(): int
    {
        $query = Student::query();

        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
        }

        return $query->count();
    }

    /** @return array<string, array{label: string, color: string, count: int, amount: float, percentage: float}> */
    public function byStatus(): array
    {
        $stats = [];
        $total = $this->totalTransactions();

        foreach (TransactionStatus::cases() as $status) {
            $query = $this->applyPeriodFilter(Transaction::where('status', $status->value));
            $count = $query->count();
            $stats[$status->value] = [
                'label' => $status->label(),
                'color' => $status->color(),
                'count' => $count,
                'amount' => (float) (clone $query)->sum('amount'),
                'percentage' => $total > 0 ? round(($count / $total) * 100, 1) : 0,
            ];
        }

        return $stats;
    }

    /** @return array{labels: array<string>, values: array<float>} */
    public function getDailyRevenue(int $days = 30): array
    {
        if ($this->dateFrom && $this->dateTo) {
            $startDate = $this->dateFrom;
            $endDate = $this->dateTo;
        } else {
            $firstTransaction = Transaction::completed()->orderBy('created_at')->first();
            $startDate = $firstTransaction?->created_at?->startOfDay() ?? now()->subDays($days - 1)->startOfDay();
            $endDate = now()->endOfDay();
        }

        if (in_array($this->period, ['all', 'year', 'quarter'])) {
            return $this->getMonthlyRevenue($startDate, $endDate);
        }

        $diffDays = $startDate->diffInDays($endDate) + 1;

        $data = Transaction::completed()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $values = [];

        for ($i = 0; $i < $diffDays; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateKey = $date->format('Y-m-d');
            $labels[] = $date->format('d.m');
            $values[] = (float) ($data[$dateKey] ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /** @return array{labels: array<string>, values: array<float>} */
    private function getMonthlyRevenue(Carbon $startDate, Carbon $endDate): array
    {
        $startMonth = $startDate->copy()->startOfMonth();
        $endMonth = $endDate->copy()->startOfMonth();

        $data = Transaction::completed()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $labels = [];
        $values = [];

        $currentMonth = $startMonth->copy();
        while ($currentMonth <= $endMonth) {
            $monthKey = $currentMonth->format('Y-m');
            $labels[] = $currentMonth->translatedFormat('M Y');
            $values[] = (float) ($data[$monthKey] ?? 0);
            $currentMonth->addMonth();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /** @return array{labels: array<string>, values: array<float>} */
    public function getRevenueByCourse(): array
    {
        $query = $this->applyPeriodFilter(Transaction::completed())
            ->where('purchasable_type', Course::class)
            ->selectRaw('purchasable_id, SUM(amount) as total')
            ->groupBy('purchasable_id')
            ->orderByDesc('total')
            ->limit(5);

        $data = $query->get();

        $courseIds = $data->pluck('purchasable_id')->toArray();
        $courses = Course::whereIn('id', $courseIds)->pluck('name', 'id');

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $courses[$item->purchasable_id] ?? 'Невідомий курс';
            $values[] = (float) $item->total;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /** @return array<array{name: string, revenue: float, count: int, percentage: float}> */
    public function topCourses(int $limit = 5): array
    {
        $totalRevenue = $this->totalRevenue();

        $query = $this->applyPeriodFilter(Transaction::completed())
            ->where('purchasable_type', Course::class)
            ->selectRaw('purchasable_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('purchasable_id')
            ->orderByDesc('total')
            ->limit($limit);

        $data = $query->get();

        $courseIds = $data->pluck('purchasable_id')->toArray();
        $courses = Course::whereIn('id', $courseIds)->pluck('name', 'id');

        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'name' => $courses[$item->purchasable_id] ?? 'Невідомий курс',
                'revenue' => (float) $item->total,
                'count' => (int) $item->count,
                'percentage' => $totalRevenue > 0 ? round(((float) $item->total / $totalRevenue) * 100, 1) : 0,
            ];
        }

        return $result;
    }

    public function formatAmount(float $amount): string
    {
        return number_format($amount, 0, ',', ' ').' ₴';
    }
}
