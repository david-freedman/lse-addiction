<?php

namespace App\Domains\Course\Enums;

enum CourseType: string
{
    case Upcoming = 'upcoming';
    case Recorded = 'recorded';
    case Free = 'free';
}
