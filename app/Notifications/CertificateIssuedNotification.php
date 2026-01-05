<?php

namespace App\Notifications;

use App\Domains\Certificate\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateIssuedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Certificate $certificate
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->certificate->load('course');

        return (new MailMessage)
            ->subject('Вітаємо! Ви отримали сертифікат')
            ->greeting('Вітаємо, '.$notifiable->name.'!')
            ->line('Ви успішно завершили курс "'.$this->certificate->course->name.'" та отримали сертифікат.')
            ->line('Ваша оцінка: '.$this->certificate->grade_level->label().' ('.$this->certificate->grade.'%)')
            ->line('Номер сертифіката: '.$this->certificate->certificate_number)
            ->action('Переглянути сертифікати', route('student.certificates'))
            ->line('Дякуємо за навчання з нами!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'certificate_id' => $this->certificate->id,
            'certificate_number' => $this->certificate->certificate_number,
            'course_id' => $this->certificate->course_id,
        ];
    }
}
