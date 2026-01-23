<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\Certificate\Models\Certificate;
use App\Domains\Course\Models\Course;
use App\Models\User;
use App\Notifications\CertificatePublishedNotification;

final class AutoPublishPendingCertificatesAction
{
    public function __invoke(Course $course, User $publisher): int
    {
        $pendingCertificates = Certificate::query()
            ->where('course_id', $course->id)
            ->pending()
            ->with('student')
            ->get();

        $count = 0;

        foreach ($pendingCertificates as $certificate) {
            $certificate->update([
                'published_at' => now(),
                'published_by' => $publisher->id,
            ]);

            $certificate->student->notify(new CertificatePublishedNotification($certificate));
            $count++;
        }

        return $count;
    }
}
