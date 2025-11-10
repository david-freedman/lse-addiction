<?php

namespace App\Domains\ActivityLog\Enums;

enum ActivitySubject: string
{
    case Customer = 'customer';
    case Course = 'course';
    case System = 'system';
}
