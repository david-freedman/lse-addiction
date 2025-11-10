<?php

namespace App\Applications\Http\Customer\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerDashboardController
{
    public function index(Request $request): View
    {
        $customer = $request->user();

        $activeCourses = $customer->courses()
            ->wherePivot('status', 'active')
            ->with(['coach', 'tags'])
            ->limit(3)
            ->get();

        return view('customer.dashboard', [
            'customer' => $customer,
            'activeCourses' => $activeCourses,
        ]);
    }
}
