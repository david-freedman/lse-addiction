<?php

namespace App\Domains\Lesson\Enums;

enum LessonType: string
{
    case Video = 'video';
    case Text = 'text';
    case Quiz = 'quiz';
    case Dicom = 'dicom';
    case Survey = 'survey';
    case QaSession = 'qa_session';

    public function label(): string
    {
        return match ($this) {
            self::Video => 'Відео',
            self::Text => 'Текст',
            self::Quiz => 'Квіз',
            self::Dicom => 'DICOM',
            self::Survey => 'Опитування',
            self::QaSession => 'Q&A сесія',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Video => '🎬',
            self::Text => '📄',
            self::Quiz => '❓',
            self::Dicom => '🏥',
            self::Survey => '📊',
            self::QaSession => '💬',
        };
    }
}
