<?php

namespace App\Policies;

use App\Domains\Homework\Models\Homework;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Models\User;

class HomeworkSubmissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, HomeworkSubmission $submission): bool
    {
        if ($this->isOwner($user, $submission)) {
            return true;
        }

        return $this->canReview($user, $submission);
    }

    public function create(User $user, Homework $homework): bool
    {
        return $homework->canStudentSubmit($user->studentProfile?->id);
    }

    public function review(User $user, HomeworkSubmission $submission): bool
    {
        return $this->canReview($user, $submission);
    }

    public function download(User $user, HomeworkSubmission $submission): bool
    {
        return $this->view($user, $submission);
    }

    private function isOwner(User $user, HomeworkSubmission $submission): bool
    {
        return $submission->student_id === $user->studentProfile?->id;
    }

    private function canReview(User $user, HomeworkSubmission $submission): bool
    {
        $course = $submission->homework->lesson->module->course;

        return app(CoursePolicy::class)->update($user, $course);
    }
}
