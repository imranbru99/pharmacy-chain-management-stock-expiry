<?php

declare(strict_types=1);

return [

    'enabled' => env('DEMO_MODE', true),

    'tenant_slug' => env('DEMO_TENANT_SLUG', 'demo-pharma-chain'),

    'password' => env('DEMO_PASSWORD', 'password'),

    /**
     * Platform settings that are blocked while demo mode is on.
     */
    'blocked_settings' => [
        'delete_tenant',
        'purge_database',
        'change_billing_gateway',
        'disable_all_modules',
    ],
];
