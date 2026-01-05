<?php

namespace App\Domains\Quiz\Data;

readonly class CheckQuizResult
{
    public function __construct(
        public int $score,
        public int $maxScore,
        public bool $passed,
        public array $questionResults
    ) {}

    public function scorePercentage(): int
    {
        if ($this->maxScore === 0) {
            return 0;
        }

        return (int) round(($this->score / $this->maxScore) * 100);
    }
}
