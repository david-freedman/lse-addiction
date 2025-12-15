<?php

namespace App\Domains\ActivityLog\Enums;

enum ActivitySubject: string
{
    case Student = 'student';
    case User = 'user';
    case Teacher = 'teacher';
    case Course = 'course';
    case Webinar = 'webinar';
    case Transaction = 'transaction';
    case Homework = 'homework';
    case HomeworkSubmission = 'homework_submission';
    case System = 'system';
}
