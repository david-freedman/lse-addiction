<?php

namespace App\Notifications;

use App\Domains\Homework\Models\HomeworkSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HomeworkReviewedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly HomeworkSubmission $submission
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $homework = $this->submission->homework;
        $lessonName = $homework->lesson->name;
        $statusLabel = $this->submission->status->label();

        $message = (new MailMessage)
            ->subject("Домашнє завдання перевірено: {$lessonName}")
            ->greeting("Вітаємо, {$notifiable->first_name}!")
            ->line("Ваше домашнє завдання з уроку \"{$lessonName}\" було перевірено.")
            ->line("Статус: **{$statusLabel}**");

        if ($this->submission->score !== null) {
            $message->line("Оцінка: **{$this->submission->score}/{$homework->max_points}**");
        }

        if ($this->submission->feedback) {
            $message->line("Коментар викладача:")
                ->line($this->submission->feedback);
        }

        return $message
            ->action('Переглянути урок', url('/'))
            ->line('Дякуємо за навчання!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'homework_reviewed',
            'submission_id' => $this->submission->id,
            'homework_id' => $this->submission->homework_id,
            'lesson_name' => $this->submission->homework->lesson->name,
            'status' => $this->submission->status->value,
            'score' => $this->submission->score,
            'feedback' => $this->submission->feedback,
        ];
    }
}
