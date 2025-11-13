<?php

namespace App\Domains\ActivityLog\Enums;

enum ActivitySubject: string
{
    case Customer = 'customer';
    case Course = 'course';
    case Transaction = 'transaction';
    case System = 'system';
}
