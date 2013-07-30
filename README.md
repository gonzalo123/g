# G. Another Full stack framework

## instalation:

### composer

```
{
    "require": {
        "gonzalo123/g": "dev-master"
    },
    "autoload": {
        "psr-0": {
            "": ["app/"]
        }
    }
}
```

### index.php:

```php
<?php

include __DIR__ . '/vendor/autoload.php';

$app = G\Application::factory([
        'config.path' => __DIR__ . '/config',
        'view.path'   => __DIR__ . '/views',
    ]);

$app->run();
```

### config and view folders

```
mkdir config
mkdir view
```
### /config/routes.yml
```
home:
  path: /
  defaults: { _controller: AppController::homeAction}

```

### /config/services.yml
```
services:
  Symfony\Component\HttpFoundation\JSonResponse:
    class: Symfony\Component\HttpFoundation\JSonResponse

  Symfony\Component\HttpFoundation\Response:
    class: Symfony\Component\HttpFoundation\Response
```