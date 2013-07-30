<?php

include __DIR__ . '/vendor/autoload.php';

$app = G\Application::factory([
        'config.path' => __DIR__ . '/config',
        'view.path'   => __DIR__ . '/views',
    ]);

$app->run();