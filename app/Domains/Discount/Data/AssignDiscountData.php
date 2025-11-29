<?php

namespace App\Domains\Discount\Data;

use App\Domains\Discount\Enums\DiscountType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class AssignDiscountData extends Data
{
    public function __construct(
        #[Required, Numeric, Exists('courses', 'id')]
        public readonly int $course_id,

        #[Required]
        public readonly DiscountType $type,

        #[Required, Numeric, Min(0.01)]
        public readonly float $value,
    ) {}

    public static function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:percentage,fixed'],
            'value' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) {
                    $type = request()->input('type');
                    if ($type === 'percentage' && $value > 100) {
                        $fail('Відсоток знижки не може перевищувати 100%.');
                    }
                },
            ],
        ];
    }
}
