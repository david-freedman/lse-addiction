<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Lesson\Data\CreateLessonData;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;

class CreateLessonAction
{
    public static function execute(Module $module, CreateLessonData $data): Lesson
    {
        return $module->lessons()->create([
            'name' => $data->name,
            'description' => $data->description,
            'content' => $data->content,
            'type' => $data->type,
            'video_url' => $data->video_url,
            'dicom_file' => $data->dicom_file,
            'duration_minutes' => $data->duration_minutes,
            'order' => $data->order ?: $module->lessons()->max('order') + 1,
            'status' => $data->status,
            'is_downloadable' => $data->is_downloadable,
            'attachments' => $data->attachments,
        ]);
    }
}
