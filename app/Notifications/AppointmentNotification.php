<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $type // 'booked', 'cancelled', 'status_updated'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appointment = $this->appointment;
        $service = $appointment->service->name;
        $date = $appointment->appointment_date->format('F j, Y \a\t g:i A');
        $status = ucfirst($appointment->status);

        $subject = match($this->type) {
            'booked'         => 'Appointment Booked Successfully',
            'cancelled'      => 'Appointment Cancellation Confirmed',
            'status_updated' => "Appointment Status Updated: {$status}",
            default          => 'Appointment Update',
        };

        $intro = match($this->type) {
            'booked'         => "Your appointment has been successfully booked.",
            'cancelled'      => "Your appointment has been cancelled as requested.",
            'status_updated' => "Your appointment status has been updated to: {$status}.",
            default          => "Your appointment has been updated.",
        };

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Hello, {$notifiable->name}!")
            ->line($intro)
            ->line("**Service:** {$service}")
            ->line("**Date & Time:** {$date}")
            ->line("**Status:** {$status}");

        if ($this->type === 'cancelled' && $appointment->cancellation_reason) {
            $mail->line("**Reason:** {$appointment->cancellation_reason}");
        }

        return $mail
            ->action('View My Appointments', url('/appointments'))
            ->line('Thank you for using our appointment system.');
    }
}