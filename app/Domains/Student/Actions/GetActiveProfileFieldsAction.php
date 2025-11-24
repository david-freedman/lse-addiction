<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Models\ProfileField;
use Illuminate\Support\Collection;

class GetActiveProfileFieldsAction
{
    public static function execute(): Collection
    {
        return ProfileField::active()->ordered()->get();
    }
}
