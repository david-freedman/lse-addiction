<?php

namespace App\Domains\Customer\Actions;

use App\Domains\Customer\Models\ProfileField;
use Illuminate\Support\Collection;

class GetActiveProfileFieldsAction
{
    public static function execute(): Collection
    {
        return ProfileField::active()->ordered()->get();
    }
}
