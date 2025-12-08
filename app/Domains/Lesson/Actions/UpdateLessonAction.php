<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Lesson\Data\UpdateLessonData;
use App\Domains\Lesson\Models\Lesson;

class UpdateLessonAction
{
    public static function execute(Lesson $lesson, UpdateLessonData $data): Lesson
    {
        $updateData = ['name' => $data->name];

        if ($data->description !== null) {
            $updateData['description'] = $data->description;
        }

        if ($data->content !== null) {
            $updateData['content'] = $data->content;
        }

        if ($data->type !== null) {
            $updateData['type'] = $data->type;
        }

        if ($data->video_url !== null) {
            $updateData['video_url'] = $data->video_url;
        }

        if ($data->dicom_file !== null) {
            $updateData['dicom_file'] = $data->dicom_file;
        }

        if ($data->duration_minutes !== null) {
            $updateData['duration_minutes'] = $data->duration_minutes;
        }

        if ($data->order !== null) {
            $updateData['order'] = $data->order;
        }

        if ($data->status !== null) {
            $updateData['status'] = $data->status;
        }

        if ($data->is_downloadable !== null) {
            $updateData['is_downloadable'] = $data->is_downloadable;
        }

        if ($data->attachments !== null) {
            $updateData['attachments'] = $data->attachments;
        }

        $lesson->update($updateData);

        return $lesson->fresh();
    }
}
