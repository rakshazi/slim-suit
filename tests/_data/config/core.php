<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'prefix' => [
            'controller' => '\Rakshazi\SlimSuit\Dummy',
            'helper' => '\Rakshazi\SlimSuit\Dummy',
            'entity' => '\Rakshazi\SlimSuit\Dummy',
        ],
        'view' => [
            'template_path' => __DIR__.'/../view/',
            'cache_path' => false,
        ],
        'database' => [
            'class' => '\Medoo\Medoo',
            'database_type' => 'mysql',
            'database_name' => 'slim_skeleton',
            'server' => 'db',
            'username' => 'slim',
            'password' => 'slim',
            'charset' => 'utf8',
            'port' => 3306,
            'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        ],
    ],
];
