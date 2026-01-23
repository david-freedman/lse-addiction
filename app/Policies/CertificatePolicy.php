<?php

namespace App\Policies;

use App\Domains\Certificate\Models\Certificate;
use App\Domains\Student\Models\Student;
use App\Models\User;

class CertificatePolicy
{
    public function download(Student $student, Certificate $certificate): bool
    {
        return $certificate->student_id === $student->id && $certificate->isVisibleToStudent();
    }

    public function revoke(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }

    public function issue(User $user): bool
    {
        return $user->isAdmin();
    }

    public function publish(User $user, Certificate $certificate): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            $teacherCourseIds = $user->getTeacherCourseIds();

            return in_array($certificate->course_id, $teacherCourseIds, true);
        }

        return false;
    }

    public function preview(User $user, Certificate $certificate): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            $teacherCourseIds = $user->getTeacherCourseIds();

            return in_array($certificate->course_id, $teacherCourseIds, true);
        }

        return false;
    }
}
