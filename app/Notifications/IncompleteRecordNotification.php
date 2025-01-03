<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncompleteRecordNotification extends Notification
{
    use Queueable;

    private $details;


    /**
     * Create a new notification instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تنبيه: سجلات غير مكتملة')
            ->line('لديك سجلات غير مكتملة في النظام:')
            ->line($this->details)
            ->action('عرض التفاصيل', url('/'))
            ->line('شكراً لاستخدامك تطبيقنا!');


    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "لديك سجل في الجدول {$this->details} لم يتم معالجته لأكثر من يوم.",
            'record_type' => $this->details,
        ];
    }
}
