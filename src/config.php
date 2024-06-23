<?php

return [
    /**
     * Frontend settings
     */
    'frontend_domain' => env('APP_FRONTEND_DOMAIN', 'localhost'),
    'frontend_url' => env('APP_FRONTEND_URL', 'http://localhost'),

    /**
     * Routing settings
     */
    'route_namespace' => '\\IOF\\DiscreteApi\\Base\\Http\\Controllers',
    'actions_namespace' => '\\IOF\\DiscreteApi\\Base\\Actions',

    /**
     * Which features to use
     */
    'features' => [
        'profile' => true,
        'email_verification' => true,
        'user_delete' => true,
        'organizations' => true,
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

    /**
     * Policies. You are free to specify any full
     * qualifyed namespace to model and policy files
     */
    'policiesToRegister' => [
        // base
        \App\Models\User::class => \IOF\DiscreteApi\Base\Policies\UserPolicy::class,
        \IOF\DiscreteApi\Base\Models\Role::class => \IOF\DiscreteApi\Base\Policies\RolePolicy::class,
        \IOF\DiscreteApi\Base\Models\Profile::class => \IOF\DiscreteApi\Base\Policies\ProfilePolicy::class,
        \IOF\DiscreteApi\Base\Models\UserEmailChange::class => \IOF\DiscreteApi\Base\Policies\UserEmailChangePolicy::class,
        \IOF\DiscreteApi\Base\Models\NotificationAlerts::class => \IOF\DiscreteApi\Base\Policies\NotificationAlertsPolicy::class,
        \IOF\DiscreteApi\Base\Models\Organization::class => \IOF\DiscreteApi\Base\Policies\OrganizationPolicy::class,
        \IOF\DiscreteApi\Base\Models\OrganizationMember::class => \IOF\DiscreteApi\Base\Policies\OrganizationMemberPolicy::class,
        \IOF\DiscreteApi\Base\Models\Workspace::class => \IOF\DiscreteApi\Base\Policies\WorkspacePolicy::class,
    ],

    /**
     * Observers. You are free to specify any full
     * qualifyed namespace to model and policy files
     */
    'observersToRegister' => [
        // base
        \App\Models\User::class => \IOF\DiscreteApi\Base\Observers\UserObserver::class,
        \IOF\DiscreteApi\Base\Models\UserEmailChange::class => \IOF\DiscreteApi\Base\Observers\UserEmailChangeObserver::class,
        \IOF\DiscreteApi\Base\Models\NotificationAlerts::class => \IOF\DiscreteApi\Base\Observers\NotificationAlertsObserver::class,
        \IOF\DiscreteApi\Base\Models\Organization::class => \IOF\DiscreteApi\Base\Observers\OrganizationObserver::class,
        \IOF\DiscreteApi\Base\Models\OrganizationMember::class => \IOF\DiscreteApi\Base\Observers\OrganizationMemberObserver::class,
        \IOF\DiscreteApi\Base\Models\Profile::class => \IOF\DiscreteApi\Base\Observers\ProfileObserver::class,
        \IOF\DiscreteApi\Base\Models\Workspace::class => \IOF\DiscreteApi\Base\Observers\WorkspaceObserver::class,
    ],

    /**
     * Filesystem manipulation options
     */
    'filesystem' => [
        'max_upload_size' => 8, // in MEGABYTES !
    ],

    /**
     * Organization options
     */
    'organization' => [
        /**
         * USAGE:
         *      DiscreteApiHelper::organization_member_role($User, $Organization) >= 9
         */
        'roles' => [
            -1 => 'ban',
            0 => 'readonly',
            5 => 'user',
            8 => 'moderator',
            9 => 'admin',
            10 => 'owner',
        ]
    ],

    /**
     * locales
     * must be equal with fronyend
     */
    'locales' => [
        'en' => [
            'title' => 'English',
            'cp' => 'en_US.UTF-8',
        ],
        'ru' => [
            'title' => 'Русский',
            'cp' => 'ru_RU.UTF-8',
        ],
    ],
];
