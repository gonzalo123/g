<?php

namespace G\Application;

use G\HttpKernel\Event\KernelEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

trait DispatcherTrait
{
    /** @var  EventDispatcher */
    protected $dispatcher;

    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);
    }

    public function addListenerOnInit($listener, $priority = 0)
    {
        $this->dispatcher->addListener(KernelEvent::EVENT_INIT, $listener, $priority);
    }

    public function addListenerOnController($listener, $priority = 0)
    {
        $this->dispatcher->addListener(KernelEvent::EVENT_CONTROLLER_DECODED, $listener, $priority);
    }

    public function addListenerOnConstructor($listener, $priority = 0)
    {
        $this->dispatcher->addListener(KernelEvent::EVENT_CONTROLLER_CONSTRUCTOR, $listener, $priority);
    }

    public function addListenerOnAction($listener, $priority = 0)
    {
        $this->dispatcher->addListener(KernelEvent::EVENT_CONTROLLER_ACTION, $listener, $priority);
    }

    public function addListenerOnResponse($listener, $priority = 0)
    {
        $this->dispatcher->addListener(KernelEvent::EVENT_RESPONSE, $listener, $priority);
    }

    public function addListenerOnFinish($listener, $priority = 0)
    {
        $this->dispatcher->addListener(KernelEvent::EVENT_FINISH, $listener, $priority);
    }

    public function addListenerOnException($listener, $priority = 0)
    {
        $this->dispatcher->addListener(KernelEvent::EVENT_EXCEPTION, $listener, $priority);
    }
}