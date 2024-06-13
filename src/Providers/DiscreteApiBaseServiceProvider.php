<?php

namespace IOF\DiscreteApi\Base\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use IOF\DiscreteApi\Base\Console\Commands\AssignUserRoleCommand;
use IOF\DiscreteApi\Base\Contracts\UserUpdateContract;
use IOF\DiscreteApi\Base\Contracts\RegisterContract;
use IOF\DiscreteApi\Base\Contracts\AuthenticateContract;
use IOF\DiscreteApi\Base\Contracts\NotificationAlertsContract;
use IOF\DiscreteApi\Base\Contracts\NotificationReadAlertsContract;
use IOF\DiscreteApi\Base\Contracts\PasswordForgotContract;
use IOF\DiscreteApi\Base\Contracts\PasswordResetContract;
use IOF\DiscreteApi\Base\Contracts\LogoutContract;
use IOF\DiscreteApi\Base\Contracts\UserDeleteContract;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiHelper;
use IOF\DiscreteApi\Base\Models\Role;
use IOF\DiscreteApi\Base\Models\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class DiscreteApiBaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'discreteapibase');
        $this->app->bind('role', function () {
            return new Role();
        });
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'discreteapibase');
        $this->loadJsonTranslationsFrom(__DIR__.'/../../lang');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
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

    /**
     * Verification Email Customization
     */
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

    /**
     * Configures a poblishes
     */
    protected function configurePublishing(): void
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                realpath(__DIR__.'/../../database/migrations') => base_path('database/migrations'),
                realpath(__DIR__.'/../../lang') => lang_path('vendor/discreteapibase'),
                realpath(__DIR__.'/../../stubs/User.php') => app_path('/Models/User.php')
            ], 'discreteapibase-install');

            $this->publishes([
                realpath(__DIR__.'/../../database/migrations') => base_path('database/migrations'),
            ], 'discreteapibase-migrations');

            $this->publishes([
                realpath(__DIR__.'/../../lang') => lang_path('vendor/discreteapibase'),
            ], 'discreteapibase-lang');

            $this->publishes([
                realpath(__DIR__.'/../../stubs/User.php') => app_path('/Models/User.php')
            ], 'discreteapibase-models');

        }
    }

    /**
     * Configure the commands offered by the application.
     */
    protected function configureCommands(): void
    {
        if (app()->runningInConsole()) {
            $this->commands([
                AssignUserRoleCommand::class
            ]);
        }
    }

    /**
     * Configure the routes offered by the application.
     */
    protected function configureRoutes(): void
    {
        $parsed = parse_url(config('app.url', 'http://localhost'));
        Route::domain($parsed['host'])
             ->middleware('api')
             ->namespace(config('discreteapibase.route_namespace'))
             ->prefix('api')
             ->group(function () {
                 $this->loadRoutesFrom(__DIR__.'/../routes.php');
             });
    }

    /**
     * Configure Policies
     */
    protected function configurePolicies(): void
    {
        if (config('discreteapibase.policiesToRegister', [])) {
            foreach (config('discreteapibase.policiesToRegister', []) as $model => $policy) {
                Gate::policy($model, $policy);
            }
        }
    }

    /**
     * Configure Observers
     */
    protected function configureObservers(): void
    {
        foreach (config('discreteapibase.observersToRegister') as $Model => $Observer) {
            if (class_exists($Model) && class_exists($Observer)) {
                /** @noinspection PhpUndefinedMethodInspection */
                $Model::observe($Observer);
            }
        }
    }

    protected function configureActions()
    {
        $actions_namespace = config('discreteapibase.actions_namespace') . '\\';
        $this->app->singleton(RegisterContract::class, $actions_namespace.'RegisterAction');
        $this->app->singleton(AuthenticateContract::class, $actions_namespace.'AuthenticateAction');
        $this->app->singleton(PasswordForgotContract::class, $actions_namespace.'PasswordForgotAction');
        $this->app->singleton(PasswordResetContract::class, $actions_namespace.'PasswordResetAction');
        $this->app->singleton(LogoutContract::class, $actions_namespace.'LogoutAction');
        $this->app->singleton(UserDeleteContract::class, $actions_namespace.'UserDeleteAction');
        $this->app->singleton(UserUpdateContract::class, $actions_namespace.'UserUpdateAction');
        $this->app->singleton(NotificationAlertsContract::class, $actions_namespace.'NotificationAlertsAction');
        $this->app->singleton(NotificationReadAlertsContract::class, $actions_namespace.'NotificationReadAlertsAction');
        $this->app->singleton(ProfileUpdateContract::class, $actions_namespace.'ProfileUpdateAction');
        $this->app->singleton(ProfileAvatarUpdateContract::class, $actions_namespace.'ProfileAvatarUpdateAction');
        $this->app->singleton(UserChangeEmailContract::class, $actions_namespace.'UserChangeEmailAction');
    }
}
