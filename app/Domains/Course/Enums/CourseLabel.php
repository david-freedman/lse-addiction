<?php

namespace App\Domains\Course\Enums;

enum CourseLabel: string
{
    case MasterClass = 'master_class';
    case Recorded = 'recorded';
    case PracticalTraining = 'practical_training';
    case Intensive = 'intensive';
    case OnlineCourse = 'online_course';
    case VideoCourse = 'video_course';

    public function label(): string
    {
        return match ($this) {
            self::MasterClass => 'Майстер-клас',
            self::Recorded => 'Курс у записі',
            self::PracticalTraining => 'Практичний тренінг',
            self::Intensive => 'Інтенсив',
            self::OnlineCourse => 'Онлайн-курс',
            self::VideoCourse => 'Відеокурс',
        };
    }
}
