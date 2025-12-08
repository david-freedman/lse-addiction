<?php

namespace App\Domains\Quiz\Enums;

enum QuestionType: string
{
    case SingleChoice = 'single_choice';
    case MultipleChoice = 'multiple_choice';
    case ImageSelect = 'image_select';
    case DragDrop = 'drag_drop';

    public function label(): string
    {
        return match ($this) {
            self::SingleChoice => 'Одна правильна відповідь',
            self::MultipleChoice => 'Кілька правильних відповідей',
            self::ImageSelect => 'Вибір зображення',
            self::DragDrop => 'Перетягування',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SingleChoice => 'Студент обирає одну відповідь зі списку',
            self::MultipleChoice => 'Студент може обрати кілька відповідей',
            self::ImageSelect => 'Студент обирає правильне зображення',
            self::DragDrop => 'Студент перетягує елементи до категорій',
        };
    }
}
