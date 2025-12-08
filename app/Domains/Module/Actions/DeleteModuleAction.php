<?php

namespace App\Domains\Module\Actions;

use App\Domains\Module\Models\Module;

class DeleteModuleAction
{
    public static function execute(Module $module): bool
    {
        return $module->delete();
    }
}
