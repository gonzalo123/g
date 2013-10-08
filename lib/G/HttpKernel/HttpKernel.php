<?php

namespace G\HttpKernel;

use G\Exception\SecurityException;
use G\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use G\HttpKernel\Event\KernelEvent;

class HttpKernel implements HttpKernelInterface, TerminableInterface
{
    private $controller;
    private $dispatcher;
    private $controllerResolver;
    private $request;
    private $event;

    public function __construct(EventDispatcher $dispatcher, ControllerResolver $controllerResolver)
    {
        $this->controllerResolver = $controllerResolver;
        $this->dispatcher         = $dispatcher;
        $this->event              = new KernelEvent($this);
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $this->dispatchEvent(KernelEvent::EVENT_INIT);
        $this->request = $request;

        $this->controller = $this->controllerResolver->getController($request);
        $this->dispatchEvent(KernelEvent::EVENT_CONTROLLER_DECODED);

        $class = new \ReflectionClass($this->controller[0]);
        if ($class->implementsInterface('G\SecureArea')) {
            try {
                $this->dispatchEvent(KernelEvent::EVENT_CONTROLLER_SECURITY);
            } catch (SecurityException $e) {
                $this->dispatchEvent(KernelEvent::EVENT_LOGIN);
                $class = new \ReflectionClass($this->controller[0]);
            }
        }

        return $this->runClass($this->getInstance($class));

    }

    private function dispatchEvent($eventId)
    {
        $this->dispatcher->dispatch($eventId, $this->event);
    }

    public function terminate(Request $request, Response $response)
    {
        $this->dispatchEvent(KernelEvent::EVENT_FINISH);
    }

    private function getInstance(\ReflectionClass $class)
    {
        $callParameters = [];
        if ($class->hasMethod('__construct')) {
            $controller     = [$this->controller[0], '__construct'];
            $callParameters = $this->controllerResolver->getArguments($this->request, $controller);
        }

        return $class->newInstanceArgs($callParameters);
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setDispatcher(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    private function runClass($instance)
    {
        $this->dispatchEvent(KernelEvent::EVENT_CONTROLLER_CONSTRUCTOR);

        $callParameters = $this->controllerResolver->getArguments($this->request, $this->controller);
        $response       = call_user_func_array([$instance, $this->controller[1]], $callParameters);
        $this->dispatchEvent(KernelEvent::EVENT_CONTROLLER_ACTION);

        if (!($response instanceof Response)) {
            $response = new Response($response);
        }

        $this->dispatchEvent(KernelEvent::EVENT_RESPONSE);

        return $response;
    }
}
