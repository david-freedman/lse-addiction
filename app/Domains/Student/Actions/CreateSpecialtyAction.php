<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Data\CreateSpecialtyData;
use App\Domains\Student\Models\Specialty;

class CreateSpecialtyAction
{
    public static function execute(CreateSpecialtyData $data): Specialty
    {
        return Specialty::create(['name' => $data->name]);
    }
}
