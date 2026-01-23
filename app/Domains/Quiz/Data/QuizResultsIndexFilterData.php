<?php

namespace App\Domains\Quiz\Data;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class QuizResultsIndexFilterData extends Data
{
    public function __construct(
        #[Nullable, StringType, In(['quizzes', 'surveys'])]
        public readonly ?string $tab = 'quizzes',

        #[Nullable, StringType, In(['passed', 'failed'])]
        public readonly ?string $status = null,

        #[Nullable, IntegerType]
        public readonly ?int $course_id = null,

        #[Nullable, IntegerType]
        public readonly ?int $module_id = null,

        #[Nullable, IntegerType]
        public readonly ?int $lesson_id = null,

        #[Nullable, IntegerType]
        public readonly ?int $quiz_id = null,

        #[Nullable, StringType, Max(255)]
        public readonly ?string $search = null,
    ) {}

    public function isQuizzesTab(): bool
    {
        return $this->tab === 'quizzes' || $this->tab === null;
    }

    public function isSurveysTab(): bool
    {
        return $this->tab === 'surveys';
    }

    public function getPassedFilter(): ?bool
    {
        return match ($this->status) {
            'passed' => true,
            'failed' => false,
            default => null,
        };
    }

    public function isFiltered(): bool
    {
        return $this->course_id !== null
            || $this->module_id !== null
            || $this->lesson_id !== null
            || $this->quiz_id !== null
            || $this->search !== null;
    }
}
