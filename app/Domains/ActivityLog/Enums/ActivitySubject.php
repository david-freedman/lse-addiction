<?php

namespace App\Domains\ActivityLog\Enums;

enum ActivitySubject: string
{
    case Student = 'student';
    case User = 'user';
    case Teacher = 'teacher';
    case Course = 'course';
    case Transaction = 'transaction';
    case System = 'system';
}
