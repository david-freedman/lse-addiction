<?php

namespace App\Domains\ActivityLog\Enums;

enum ActivitySubject: string
{
    case Student = 'student';
    case User = 'user';
    case Teacher = 'teacher';
    case Course = 'course';
    case Webinar = 'webinar';
    case Transaction = 'transaction';
    case Homework = 'homework';
    case HomeworkSubmission = 'homework_submission';
    case Module = 'module';
    case Lesson = 'lesson';
    case Quiz = 'quiz';
    case Certificate = 'certificate';
    case Comment = 'comment';
    case StudentGroup = 'student_group';
    case Discount = 'discount';
    case Admin = 'admin';
    case System = 'system';
}
