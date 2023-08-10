<?php

namespace App\Notifications;

use App\Mail\InviteNewUserMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invite;
use Illuminate\Contracts\Mail\Mailable;

class InviteNotification extends Notification
{
    use Queueable;
    public string $inviteUrl, $invitedBy, $syndicateName;
    public bool $newUser;
    public Invite $invite;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invite $invite, bool $newUser)
    {
        $this->newUser = $newUser;
        $this->invite = $invite;
        $this->inviteUrl = route('invite', ['code' => $invite->invite_code]);
        $this->invitedBy = $invite->invitedBy->name();
        $this->syndicateName = $invite->syndicate->name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->newUser ? ['database'] : ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): Mailable
    {
        return (new InviteNewUserMailable($this->invite, $this->newUser))
                    ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'inviteUrl' => $this->inviteUrl,
            'invitedBy' => $this->invitedBy,
            'syndicateName' => $this->syndicateName
        ];
    }
}
