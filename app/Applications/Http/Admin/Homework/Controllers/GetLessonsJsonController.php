<?php

namespace App\Applications\Http\Admin\Homework\Controllers;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;

final class GetLessonsJsonController
{
    public function __invoke(Module $module): JsonResponse
    {
        $lessons = Lesson::query()
            ->where('module_id', $module->id)
            ->whereHas('homework')
            ->orderBy('order')
            ->get(['id', 'name', 'order']);

        return response()->json($lessons);
    }
}
