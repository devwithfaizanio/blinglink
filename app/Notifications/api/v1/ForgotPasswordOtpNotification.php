<?php

namespace App\Notifications\api\v1;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $token;
    public string $otp;

    public function __construct(string $token, string $otp)
    {
        $this->token = $token;
        $this->otp = $otp;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Password')
            ->view('mails.send-forgot-password-mail-otp', [
                'token' => $this->token,
                'otp'   => $this->otp,
            ]);
    }

}
