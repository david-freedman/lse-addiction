<?php

namespace App\Domains\Customer\Enums;

enum ProfileFieldType: string
{
    case Text = 'text';
    case Select = 'select';
    case Number = 'number';
    case Date = 'date';
}
