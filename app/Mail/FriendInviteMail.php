<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FriendInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $inviter,
        public string $shareLink,
        public ?string $customMessage = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->inviter->f_name} invited you to join BlingLink!",
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px;'>
                    <h2 style='color: #4F46E5;'>You've been invited to BlingLink!</h2>
                    <p>Hi there,</p>
                    <p><strong>{$this->inviter->f_name}</strong> invited you to join them on BlingLink - the premier networking & social platform.</p>
                    " . ($this->customMessage ? "<blockquote style='background: #f9f9f9; padding: 10px; border-left: 4px solid #4F46E5;'>\"{$this->customMessage}\"</blockquote>" : "") . "
                    <p>Use referral code: <strong style='font-size: 18px; color: #4F46E5;'>{$this->inviter->referral_code}</strong></p>
                    <div style='margin: 25px 0;'>
                        <a href='{$this->shareLink}' style='background-color: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;'>Join BlingLink Now</a>
                    </div>
                    <p style='color: #666; font-size: 12px;'>If you didn't expect this invitation, you can safely ignore this email.</p>
                </div>
            "
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
