<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Data\UpdateSpecialtyData;
use App\Domains\Student\Models\Specialty;

class UpdateSpecialtyAction
{
    public static function execute(Specialty $specialty, UpdateSpecialtyData $data): Specialty
    {
        $specialty->update(['name' => $data->name]);

        return $specialty->fresh();
    }
}
