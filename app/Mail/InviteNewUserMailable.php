<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invite;

class InviteNewUserMailable extends Mailable
{
    use Queueable, SerializesModels;
    public Invite $invite;
    public string $inviteUrl;
    public bool $newUser;

    /**
     * Create a new message instance.
     */
    public function __construct(Invite $invite, bool $newUser)
    {
        $this->invite = $invite;
        $this->newUser = $newUser;
        if($newUser)
        {
            $this->inviteUrl = route('register');
        } else {
            $this->inviteUrl = route('invite', ['code' => $invite->invite_code]);
        }
        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->invite->invitedBy->name()} invited you to a syndicate.",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invitenewuser',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
