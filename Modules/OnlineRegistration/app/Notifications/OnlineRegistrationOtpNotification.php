<?php

namespace Modules\OnlineRegistration\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\OnlineRegistration\Models\OnlineRegistration;

class OnlineRegistrationOtpNotification extends Notification
{
    use Queueable;

    public function __construct(public OnlineRegistration $registration)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('HMS-ERP Pre-registration OTP')
            ->line('Your pre-registration OTP code is: ' . $this->registration->otp_code)
            ->line('This code will expire in 10 minutes.')
            ->action('Verify registration', route('online-registration.verify.form', ['id' => $this->registration->id]))
            ->line('If you did not request this registration, please ignore this email.');
    }
}
