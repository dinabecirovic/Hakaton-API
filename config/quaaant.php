<?php

use Illuminate\Support\Str;

return [

    'git_author_email_domain' => 'quaaant.com',
    'gitlab_external_host' => env('GITLAB_EXTERNAL_HOST'),
    'gitlab_external_ssh_port' => env('GITLAB_EXTERNAL_SSH_PORT'),
    'gitlab_systemhook_token' => env('GITLAB_SYSTEMHOOK_TOKEN'),
    'gitlab_disable' => env('GITLAB_DISABLE', false),

    'spa_app_url' => env('SPA_URL'),

    'cdn_server_url' => env('CDN_SERVER_URL'),
    'cdn_server_auth_token' => env('CDN_SERVER_AUTH_TOKEN'),

    'redirect_after_email_welcome' => env('REDIRECT_AFTER_EMAIL_WELCOME'),
    'redirect_after_email_verify' => env('REDIRECT_AFTER_EMAIL_VERIFY'),
    'redirect_404' => env('REDIRECT_404'),

    'url_email_reset' => env('URL_EMAIL_RESET'),

    'feedback_email_forward' =>  env('FEEDBACK_EMAIL_FORWARD', 'feedback@quaaant.com' ),

    'woocommerce_url' =>  env('WOOCOMMERCE_URL'),
    'woocommerce_consumer_key' =>  env('WOOCOMMERCE_CONSUMER_KEY'),
    'woocommerce_consumer_secret' =>  env('WOOCOMMERCE_CONSUMER_SECRET'),
    'woocommerce_disable' => env('WOOCOMMERCE_DISABLE'),

    'mautic_url' =>  env('MAUTIC_URL'),
    'mautic_username' =>  env('MAUTIC_AUTH_USERNAME'),
    'mautic_password' =>  env('MAUTIC_AUTH_PASSWORD'),
    'mautic_owner_id' =>  env('MAUTIC_OWNER_ID'),
    'mautic_disable' => env('MAUTIC_DISABLE'),

    'recaptcha_disable' => env('RECAPTCHA_DISABLE'),

    'posthog_api_key' => env('POSTHOG_API_KEY'),
    'posthog_server_url' => env('POSTHOG_SERVER_URL'),

    'mail-templates' => [
        'welcome-trial' => env('MAIL_TEMPLATE_WELCOME_TRIAL'),
        'welcome-subscribed' => env('MAIL_TEMPLATE_WELCOME_SUBSCRIBED'),
        'email-verification' => env('MAIL_TEMPLATE_VERIFICATION'),
        'password-reset' => env('MAIL_TEMPLATE_PASSWORD_RESET'),
        'project-shared' => env('MAIL_TEMPLATE_PROJECT_SHARED'),
        'subscription-cancel' => env('MAIL_TEMPLATE_SUBSCRIPTION_CANCEL'),
        'subscription-charged' => env('MAIL_TEMPLATE_SUBSCRIPTION_CHARGED'),
        'subscription-charge-failed' => env('MAIL_TEMPLATE_SUBSCRIPTION_CHARGE_FAILED'),
        'subscription-subscribe' => env('MAIL_TEMPLATE_SUBSCRIPTION_SUBSCRIBE'),
        'subscription-subscribe-promo' => env('MAIL_TEMPLATE_SUBSCRIPTION_SUBSCRIBE_PROMO'),
        'subscription-subscribe-with-card' => env('MAIL_TEMPLATE_SUBSCRIPTION_SUBSCRIBE_WITH_CARD'),
        'subscription-trial-end-reminder' => env('MAIL_TEMPLATE_SUBSCRIPTION_TRIAL_END_REMINDER'),
    ],

    'default_country_code' => env('DEFAULT_COUNTRY_CODE', 'SI'),
    'devrev_api_url' => env('DEVREV_API_URL', 'https://api.devrev.ai/'),
    'devrev_application_access_token' => env('DEVREV_APPLICATION_ACCESS_TOKEN'),
    'demo_projects' => explode(',', env('DEMO_PROJECTS', '')),

    "mautic_category_map" => [
        "Developer" => 21,
        "Designer" => 22,
        "Marketer" => 23,
        "Team lead" => 24,
        "Product manager" => 25,
    ],

    "mautic_tag_map" => [
        // --
        'Teammates' => 'coll-teammates',
        'Clients' => 'coll-clients',
        'Just myself' => 'coll-just-myself',
        // --
        'Design collaboration' => 'fit-collaboration',
        'Asset management' => 'fit-assets',
        'Design-to-code' => 'fit-design-to-code',
        'Design versioning' => 'fit-versioning',
    ],

    // Ordered by weight in Mautic
    "mautic_stage_map" => [
        'integration_photoshop_installed' => 1,
        'integration_illustrator_installed' => 1,
        'integration_quaaant-electron_installed' => 1,
        'file_created' => 2,
        'file_published' => 3,
        'asset_created' => 4,
        'file_shared' => 5,
        'asset_downloaded' => 6,
    ],

    "figma" => [
        'api_url' => env('FIGMA_API_URL'),
        'oauth_api_url' => env('FIGMA_OAUTH_API_URL'),
        'client_id' => env('FIGMA_CLIENT_ID'),
        'client_secret' => env('FIGMA_CLIENT_SECRET'),
        'redirect_uri' => env('FIGMA_REDIRECT_URI'),
    ],

];
