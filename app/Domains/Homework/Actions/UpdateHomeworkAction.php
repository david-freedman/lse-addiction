<?php

namespace App\Domains\Homework\Actions;

use App\Domains\Homework\Data\UpdateHomeworkData;
use App\Domains\Homework\Models\Homework;

final class UpdateHomeworkAction
{
    public function __invoke(Homework $homework, UpdateHomeworkData $data): Homework
    {
        $updateData = array_filter([
            'description' => $data->description,
            'response_type' => $data->response_type,
            'max_points' => $data->max_points,
            'passing_score' => $data->passing_score,
            'max_attempts' => $data->max_attempts,
            'deadline_at' => $data->deadline_at,
            'is_required' => $data->is_required,
            'allowed_extensions' => $data->allowed_extensions,
            'max_file_size_mb' => $data->max_file_size_mb,
            'max_files' => $data->max_files,
        ], fn ($value) => $value !== null);

        $homework->update($updateData);

        return $homework->fresh();
    }
}
