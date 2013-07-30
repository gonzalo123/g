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

### Controller
```
<?php

class AppController
{
    public function homeAction()
    {
        return "AppController::home";
    }
}
```

#### Complex controller

another route:
```
hello:
  path: /hello/{name}
  defaults: { _controller: AppController::helloAction}
```

```
class AppController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function helloAction($name, JsonResponse $response)
    {
        return $response->setData([$name, 1, 2]);
    }
}
```

### RESTFull controllers

we add to our services.yml:
```
restResources:
  book:
    path: /resource/book
    class: BookResource
```

And we define our BookResource

```
<?php

class BookResource
{
    public function getAction($id)
    {
        return "BookResource:getAction " . $id;
    }

    public function getAllAction()
    {
        return "BookResource:getAllAction";
    }

    public function saveAction($id)
    {
        return "BookResource:saveAction " . $id;
    }

    public function addAction()
    {
        return "BookResource:addAction";
    }

    public function deleteAction($id)
    {
        return "BookResource:deleteAction " . $id;
    }
}
```