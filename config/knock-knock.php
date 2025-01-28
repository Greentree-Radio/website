<?php

return [
    '*' => [
        'enabled' => false,
        'enableCpProtection' => false,
        'loginPath' => 'restricted-access',
        'template' => '_knock-knock.twig',
        'siteSettings' => [],
        'checkInvalidLogins' => false,
        'invalidLoginWindowDuration' => '3600',
        'maxInvalidLogins' => 10,
        'allowIps' => ['81.82.199.174', '127.0.0.1'],
        'denyIps' => [],
        'useRemoteIp' => true,
        'protectedUrls' => [],
        'unprotectedUrls' => [
              '/frontend/img/site/logo.png',
            '/frontend/site-B6OP_x-U.css',
            '/frontend/site-CrNUXQ4e.js',
        ],
    ],
    'production' => [
        'enabled' => false,
        'password' => getenv('SITE_PASSWORD'),
    ],
    'staging' => [
        'enabled' => false,
        'password' => getenv('SITE_PASSWORD'),

    ],
    'dev' => [
        'enabled' => false,
        'password' => getenv('SITE_PASSWORD'),
    ]
];

