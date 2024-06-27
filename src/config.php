<?php

use App\Models\User;
use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Models\NotificationAlerts;
use IOF\DiscreteApi\Base\Models\Organization;
use IOF\DiscreteApi\Base\Models\OrganizationMember;
use IOF\DiscreteApi\Base\Models\Profile;
use IOF\DiscreteApi\Base\Models\Role;
use IOF\DiscreteApi\Base\Models\UserEmailChange;
use IOF\DiscreteApi\Base\Observers\NotificationAlertsObserver;
use IOF\DiscreteApi\Base\Observers\OrganizationMemberObserver;
use IOF\DiscreteApi\Base\Observers\OrganizationObserver;
use IOF\DiscreteApi\Base\Observers\ProfileObserver;
use IOF\DiscreteApi\Base\Observers\UserEmailChangeObserver;
use IOF\DiscreteApi\Base\Observers\UserObserver;
use IOF\DiscreteApi\Base\Policies\NotificationAlertsPolicy;
use IOF\DiscreteApi\Base\Policies\OrganizationMemberPolicy;
use IOF\DiscreteApi\Base\Policies\OrganizationPolicy;
use IOF\DiscreteApi\Base\Policies\ProfilePolicy;
use IOF\DiscreteApi\Base\Policies\RolePolicy;
use IOF\DiscreteApi\Base\Policies\UserEmailChangePolicy;
use IOF\DiscreteApi\Base\Policies\UserPolicy;

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
        '2fa' => 'email', // email | google | false
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
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Profile::class => ProfilePolicy::class,
        UserEmailChange::class => UserEmailChangePolicy::class,
        NotificationAlerts::class => NotificationAlertsPolicy::class,
        Organization::class => OrganizationPolicy::class,
        OrganizationMember::class => OrganizationMemberPolicy::class,
    ],

    /**
     * Observers. You are free to specify any full
     * qualifyed namespace to model and policy files
     */
    'observersToRegister' => [
        // base
        User::class => UserObserver::class,
        UserEmailChange::class => UserEmailChangeObserver::class,
        NotificationAlerts::class => NotificationAlertsObserver::class,
        Organization::class => OrganizationObserver::class,
        OrganizationMember::class => OrganizationMemberObserver::class,
        Profile::class => ProfileObserver::class,
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
        ],
        /**
         * name for routes relations and field names
         *
         * possible variants: groups | teams | projects | organizations
         *
         *     WARNING          !WARNING          !WARNING
         *
         *     DO NOT CHANGE AFTER MIGRATION IMPLEMENTATION !
         *     AFFECTS TO `profiles` TABLE AND RELATION NAMES !
         */
        'plural_name' => Str::plural('organization'),
        'singular_name' => Str::singular('organization'),
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
