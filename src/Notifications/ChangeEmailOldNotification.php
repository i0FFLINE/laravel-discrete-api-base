<?php

namespace IOF\DiscreteApi\Base\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ChangeEmailOldNotification extends Notification
{
    public User $User;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $User)
    {
        $this->User = $User;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array|string
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): ?MailMessage
    {
        $change = $this->User->emailChanges()->latest()->first();
        if (! is_null($change)) {
            $url = url(config('discreteapibase.frontend_url').'/auth/change-email/'.$change->new_email, [], true);

            return $this->buildMailMessage($url);
        }

        return null;
    }

    /**
     * Build the mail representation of the notification.
     */
    protected function buildMailMessage(string $url): ?MailMessage
    {
        $change = $this->User->emailChanges()->latest()->first();
        return ! is_null($change) ? (new MailMessage())->subject(Lang::get('Change Email Notification'))->line(Lang::get('You are receiving this email because we received a change email request for your account.'))->action(Lang::get('I Confirm Change Email'), $url)->line(Lang::get('The OLD Email is:', ['email' => $this->User->email]))->line(Lang::get('The NEW Email is:', ['email' => $change->new_email]))->line(Lang::get('if you have not requested a change to your account email address, no further action is required.')) : null;
    }
}
