<?php

namespace IOF\DiscreteApi\Base\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class TwoFactorSecretNotification extends Notification
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

    public function toMail(mixed $notifiable): MailMessage
    {
        $secret = Str::random(20);
        $this->User->forceFill(['two_factor_secret' => Hash::make($secret)])->save();
        return $this->buildMailMessage($secret);
    }

    protected function buildMailMessage(string $secret): ?MailMessage
    {
        return (new MailMessage())
            ->subject(Lang::get('Two-factor authentication'))
            ->line(Lang::get('You are receiving this email because we received a authentication request for your account') . '.')
            ->line(Lang::get('# Your two-factor code is'))
            ->line(Lang::get('# `:code`', ['code' => $secret]));
    }
}
