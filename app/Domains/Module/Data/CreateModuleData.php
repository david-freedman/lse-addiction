<?php

namespace App\Domains\Module\Data;

use App\Domains\Module\Enums\ModuleStatus;
use App\Domains\Module\Enums\ModuleUnlockRule;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class CreateModuleData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public readonly string $name,

        #[Nullable, StringType]
        public readonly ?string $description = null,

        public readonly int $order = 0,

        public readonly ModuleStatus $status = ModuleStatus::Active,

        public readonly bool $has_final_test = false,

        public readonly ModuleUnlockRule $unlock_rule = ModuleUnlockRule::None,
    ) {}
}
