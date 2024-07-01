<?php

namespace IOF\DiscreteApi\Base\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use IOF\DiscreteApi\Base\TwoFactorAuthProviders\TwoFAProviderInterface;

class TwoFactorSecretNotification extends Notification
{
    public User $User;
    public mixed $twofaProvider;

    public function __construct(User $User)
    {
        $this->User = $User;
        $TwofaProvider = config('discreteapibase.features.2fa');
        $this->twofaProvider = new $TwofaProvider();
        unset($TwofaProvider);
        if (!$this->twofaProvider instanceof TwoFAProviderInterface) {
            $this->twofaProvider = null;
        }
    }

    public function via(): array|string
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): ?MailMessage
    {
        if (!is_null($this->twofaProvider)) {
            $secret = $this->twofaProvider->generateSecret($this->User, 20);
            return $this->buildMailMessage($secret);
        }
        return null;
    }

    protected function buildMailMessage(string $secret): ?MailMessage
    {
        return (new MailMessage())->subject(Lang::get('Two-factor authentication'))->line(Lang::get('You are receiving this email because we received a authentication request for your account') . '.')->line(Lang::get('# Your two-factor code is'))->line(Lang::get('# `:code`', ['code' => $secret]));
    }
}
