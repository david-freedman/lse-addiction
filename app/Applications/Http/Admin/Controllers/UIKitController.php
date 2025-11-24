<?php

namespace App\Applications\Http\Admin\Controllers;

use Illuminate\View\View;

class UIKitController
{
    public function index(): View
    {
        $demoTableData = [
            ['id' => 1, 'name' => 'Іван Петренко', 'email' => 'ivan@example.com', 'role' => 'Адміністратор', 'status' => 'active'],
            ['id' => 2, 'name' => 'Марія Коваленко', 'email' => 'maria@example.com', 'role' => 'Коуч', 'status' => 'active'],
            ['id' => 3, 'name' => 'Олександр Шевченко', 'email' => 'oleksandr@example.com', 'role' => 'Студент', 'status' => 'inactive'],
            ['id' => 4, 'name' => 'Анна Бондаренко', 'email' => 'anna@example.com', 'role' => 'Студент', 'status' => 'active'],
            ['id' => 5, 'name' => 'Дмитро Мельник', 'email' => 'dmytro@example.com', 'role' => 'Коуч', 'status' => 'pending'],
        ];

        $monthlyData = [
            ['month' => '2025-06', 'value' => 4500],
            ['month' => '2025-07', 'value' => 5200],
            ['month' => '2025-08', 'value' => 4800],
            ['month' => '2025-09', 'value' => 6100],
            ['month' => '2025-10', 'value' => 7300],
            ['month' => '2025-11', 'value' => 8500],
        ];

        $categoryData = [
            ['category' => 'Психологія', 'count' => 45],
            ['category' => 'Групова терапія', 'count' => 32],
            ['category' => 'Індивідуальна', 'count' => 28],
            ['category' => 'Онлайн', 'count' => 52],
            ['category' => 'Офлайн', 'count' => 18],
        ];

        return view('admin.ui-kit', compact('demoTableData', 'monthlyData', 'categoryData'));
    }
}
