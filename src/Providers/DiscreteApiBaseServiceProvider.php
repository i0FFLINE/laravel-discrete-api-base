<?php

namespace IOF\DiscreteApi\Base\Providers;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use IOF\DiscreteApi\Base\Console\Commands\AssignUserRoleCommand;
use IOF\DiscreteApi\Base\Contracts\Auth\NotificationAlerts\NotificationAlertsContract;
use IOF\DiscreteApi\Base\Contracts\Auth\NotificationAlerts\NotificationReadAlertsContract;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationCreateContract;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationsContract;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationSwitchContract;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationUpdateContract;
use IOF\DiscreteApi\Base\Contracts\Auth\Profile\ProfileAvatarUpdateContract;
use IOF\DiscreteApi\Base\Contracts\Auth\Profile\ProfileUpdateContract;
use IOF\DiscreteApi\Base\Contracts\Auth\User\LogoutContract;
use IOF\DiscreteApi\Base\Contracts\Auth\User\User2faContract;
use IOF\DiscreteApi\Base\Contracts\Auth\User\UserChangeEmailContract;
use IOF\DiscreteApi\Base\Contracts\Auth\User\UserDeleteContract;
use IOF\DiscreteApi\Base\Contracts\Auth\User\UserUpdateContract;
use IOF\DiscreteApi\Base\Contracts\Guest\AuthenticateContract;
use IOF\DiscreteApi\Base\Contracts\Guest\PasswordForgotContract;
use IOF\DiscreteApi\Base\Contracts\Guest\PasswordResetContract;
use IOF\DiscreteApi\Base\Contracts\Guest\RegisterContract;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiHelper;
use IOF\DiscreteApi\Base\Http\Middleware\PreloadUserData;
use IOF\DiscreteApi\Base\Http\Middleware\SetLocale;
use IOF\DiscreteApi\Base\Models\PersonalAccessToken;
use IOF\DiscreteApi\Base\Models\Role;
use Laravel\Sanctum\Sanctum;

class DiscreteApiBaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'discreteapibase');
        $this->app->bind('role', function () {
            return new Role();
        });
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'discreteapibase');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../../lang');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        $this->modifyVerificationEmailNotification();
        //
        $this->configurePublishing();
        $this->configureCommands();
        $this->configureRoutes();
        $this->configurePolicies();
        $this->configureObservers();
        $this->configureActions();
    }

    protected function modifyVerificationEmailNotification(): void
    {
        VerifyEmail::createUrlUsing(function ($notifiable) {
            $verification_url = URL::temporarySignedRoute('verification.verify', Carbon::now()->addMinutes(config('auth.verification.expire', 60)), ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]);
            $parsed = DiscreteApiHelper::detail_url($verification_url);
            $parsed['host'] = config('discreteapibase.frontend_domain');
            $parsed['path'] = preg_replace("/^\/api\/auth\/user\/email-verification/iu", "/auth/confirm-email", $parsed['path']);
            return DiscreteApiHelper::join_detailed_url($parsed);
        });
    }

    protected function configurePublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                realpath(__DIR__ . '/../../database/migrations') => base_path('database/migrations'),
                realpath(__DIR__ . '/../../lang') => lang_path('vendor/discreteapibase'),
                realpath(__DIR__ . '/../../stubs/User.php') => app_path('/Models/User.php'),
                realpath(__DIR__ . '/../config.php') => app_path('/config/discreteapibase.php'),
            ], 'discreteapibase-install');

            $this->publishes([
                realpath(__DIR__ . '/../../database/migrations') => base_path('database/migrations'),
            ], 'discreteapibase-migrations');

            $this->publishes([
                realpath(__DIR__ . '/../../lang') => lang_path('vendor/discreteapibase'),
            ], 'discreteapibase-lang');

            $this->publishes([
                realpath(__DIR__ . '/../../stubs/User.php') => app_path('/Models/User.php')
            ], 'discreteapibase-models');

            $this->publishes([
                realpath(__DIR__ . '/../config.php') => app_path('/config/discreteapibase.php')
            ], 'discreteapibase-config');
        }
    }

    protected function configureCommands(): void
    {
        if (app()->runningInConsole()) {
            $this->commands([
                AssignUserRoleCommand::class
            ]);
        }
    }

    protected function configureRoutes(): void
    {
        $parsed = DiscreteApiHelper::detail_url(config('app.url', 'http://localhost'));
        Route::domain($parsed['host'])->middleware([
            'api',
            SetLocale::class,
            PreloadUserData::class
        ])->namespace(config('discreteapibase.route_namespace'))->prefix('api')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        });
    }

    protected function configurePolicies(): void
    {
        if (config('discreteapibase.policiesToRegister', [])) {
            foreach (config('discreteapibase.policiesToRegister', []) as $model => $policy) {
                Gate::policy($model, $policy);
            }
        }
    }

    protected function configureObservers(): void
    {
        if (config('discreteapibase.observersToRegister', [])) {
            foreach (config('discreteapibase.observersToRegister') as $Model => $Observer) {
                if (class_exists($Model) && class_exists($Observer)) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    $Model::observe($Observer);
                }
            }
        }
    }

    protected function configureActions(): void
    {
        $actions_namespace = config('discreteapibase.actions_namespace') . '\\';
        // Guest Actions
        $this->app->singleton(RegisterContract::class, $actions_namespace . 'Guest\\RegisterAction');
        $this->app->singleton(AuthenticateContract::class, $actions_namespace . 'Guest\\AuthenticateAction');
        $this->app->singleton(PasswordForgotContract::class, $actions_namespace . 'Guest\\PasswordForgotAction');
        $this->app->singleton(PasswordResetContract::class, $actions_namespace . 'Guest\\PasswordResetAction');
        // Auth Actions
        $this->app->singleton(LogoutContract::class, $actions_namespace . 'Auth\\LogoutAction');
        // Auth\User Actions
        $this->app->singleton(UserDeleteContract::class, $actions_namespace . 'Auth\\User\\UserDeleteAction');
        $this->app->singleton(UserUpdateContract::class, $actions_namespace . 'Auth\\User\\UserUpdateAction');
        $this->app->singleton(UserChangeEmailContract::class, $actions_namespace . 'Auth\\User\\UserChangeEmailAction');
        $this->app->singleton(User2faContract::class, $actions_namespace . 'Auth\\User\\User2faAction');
        // Auth\NotificationAlerts Actions
        $this->app->singleton(NotificationAlertsContract::class, $actions_namespace . 'Auth\\NotificationAlerts\\NotificationAlertsAction');
        $this->app->singleton(NotificationReadAlertsContract::class, $actions_namespace . 'Auth\\NotificationAlerts\\NotificationReadAlertsAction');
        // Auth\Profile Actions
        $this->app->singleton(ProfileUpdateContract::class, $actions_namespace . 'Auth\\Profile\\ProfileUpdateAction');
        $this->app->singleton(ProfileAvatarUpdateContract::class, $actions_namespace . 'Auth\\Profile\\ProfileAvatarUpdateAction');
        // Auth\Organization Actions
        $this->app->singleton(OrganizationsContract::class, $actions_namespace . 'Auth\\Organizations\\OrganizationsAction');
        $this->app->singleton(OrganizationCreateContract::class, $actions_namespace . 'Auth\\Organizations\\OrganizationCreateAction');
        $this->app->singleton(OrganizationUpdateContract::class, $actions_namespace . 'Auth\\Organizations\\OrganizationUpdateAction');
        $this->app->singleton(OrganizationSwitchContract::class, $actions_namespace . 'Auth\\Organizations\\OrganizationSwitchAction');
    }
}
