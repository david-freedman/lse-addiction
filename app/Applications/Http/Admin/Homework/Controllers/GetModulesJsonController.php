<?php

namespace App\Applications\Http\Admin\Homework\Controllers;

use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;

final class GetModulesJsonController
{
    public function __invoke(int $courseId): JsonResponse
    {
        $modules = Module::query()
            ->where('course_id', $courseId)
            ->whereHas('lessons.homework')
            ->orderBy('order')
            ->get(['id', 'name', 'order']);

        return response()->json($modules);
    }
}
