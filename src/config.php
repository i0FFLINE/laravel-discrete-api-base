<?php

return [
    /**
     * Frontend settings
     */
    'frontend_domain'               => env('APP_FRONTEND_DOMAIN', 'localhost'),
    'frontend_url'                  => env('APP_FRONTEND_URL', 'http://localhost'),

    /**
     * Routing settings
     */
    'route_namespace'               => '\\IOF\\DiscreteApi\\Base\\Http\\Controllers',
    'actions_namespace'             => '\\IOF\\DiscreteApi\\Base\\Actions',

    /**
     * Global Account settings
     */
    'account' => [
        /**
         * What to use as login username
         */
        'username' => 'email',
        /**
         * Which features to use
         */
        'features' => [
            'profile' => true,
            'email_verification' => true,
            'user_delete' => true,
        ],
    ],

    /**
     * Global Role setting
     */
    'roles' => [
        /**
         * Roles (see RoleObserver)
         */
        'default_role' => 'user',
        'super_role' => 'super',
        'admin_role' => 'admin',
        'support_role' => 'support',
        'user_role' => 'user',
    ],

    /*
     * Policies. You are free to specify any full
     * qualifyed namespace to model and policy files
     */
    'policiesToRegister' => [
        // base
        \App\Models\User::class                                 => \IOF\DiscreteApi\Base\Policies\UserPolicy::class,
        \IOF\DiscreteApi\Base\Models\Role::class                => \IOF\DiscreteApi\Base\Policies\RolePolicy::class,
        \IOF\DiscreteApi\Base\Models\Profile::class             => \IOF\DiscreteApi\Base\Policies\ProfilePolicy::class,
        \IOF\DiscreteApi\Base\Models\UserEmailChange::class     => \IOF\DiscreteApi\Base\Policies\UserEmailChangePolicy::class,
        \IOF\DiscreteApi\Base\Models\NotificationAlerts::class  => \IOF\DiscreteApi\Base\Policies\NotificationAlertsPolicy::class,
    ],

    /**
     * Observers. You are free to specify any full
     * qualifyed namespace to model and policy files
     */
    'observersToRegister' => [
        // base
        \App\Models\User::class                             => \IOF\DiscreteApi\Base\Observers\UserObserver::class,
        \IOF\DiscreteApi\Base\Models\Role::class            => \IOF\DiscreteApi\Base\Observers\RoleObserver::class,
        \IOF\DiscreteApi\Base\Models\Profile::class         => \IOF\DiscreteApi\Base\Observers\ProfileObserver::class,
        \IOF\DiscreteApi\Base\Models\UserEmailChange::class => \IOF\DiscreteApi\Base\Observers\UserEmailChangeObserver::class,
    ],

];
