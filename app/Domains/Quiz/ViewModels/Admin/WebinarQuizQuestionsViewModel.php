<?php

namespace App\Domains\Quiz\ViewModels\Admin;

use App\Domains\Quiz\Models\Quiz;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Database\Eloquent\Collection;

readonly class WebinarQuizQuestionsViewModel
{
    public function __construct(
        private Webinar $webinar,
    ) {}

    public function webinar(): Webinar
    {
        return $this->webinar;
    }

    public function quiz(): ?Quiz
    {
        return $this->webinar->quiz;
    }

    public function questions(): Collection
    {
        if (!$this->quiz()) {
            return new Collection();
        }

        return $this->quiz()->questions()->with('answers')->orderBy('order')->get();
    }

    public function totalPoints(): int
    {
        return $this->questions()->sum('points');
    }

    public function questionsCount(): int
    {
        return $this->questions()->count();
    }
}
