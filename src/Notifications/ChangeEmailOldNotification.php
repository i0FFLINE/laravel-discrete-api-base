<?php

namespace IOF\DiscreteApi\Base\Notifications;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class ChangeEmailOldNotification extends Notification
{
    public User $User;

    public function __construct(User $User)
    {
        $this->User = $User;
    }

    public function via(): array|string
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): ?MailMessage
    {
        $change = $this->User->emailChanges()->latest()->first();
        if (! is_null($change)) {
            //            $url = url(config('discreteapibase.frontend_url').'/auth/change-email/'.$change->new_email, [], true);
            $url = URL::temporarySignedRoute('user.change.email', Carbon::now()->addMinutes(60), [
                'id' => $change->getKey(),
                'hash' => sha1($change->new_email),
                'email' => $change->new_email
            ]);
            return $this->buildMailMessage($url);
        }

        return null;
    }

    protected function buildMailMessage(string $url): ?MailMessage
    {
        $change = $this->User->emailChanges()->latest()->first();
        return ! is_null($change) ? (new MailMessage())->subject(Lang::get('Change Email Notification'))->line(Lang::get('You are receiving this email because we received a change email request for your account.'))->action(Lang::get('I Confirm Change Email'), $url)->line(Lang::get('The OLD Email is:', ['email' => $this->User->email]))->line(Lang::get('The NEW Email is:', ['email' => $change->new_email]))->line(Lang::get('if you have not requested a change to your account email address, no further action is required.')) : null;
    }
}
