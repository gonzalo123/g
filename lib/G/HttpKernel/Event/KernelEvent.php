<?php

namespace G\HttpKernel\Event;

use G\HttpKernel\HttpKernel;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class KernelEvent extends Event
{
    const EVENT_INIT                   = 0;
    const EVENT_CONTROLLER_DECODED     = 1;
    const EVENT_CONTROLLER_CONSTRUCTOR = 2;
    const EVENT_CONTROLLER_ACTION      = 3;
    const EVENT_RESPONSE               = 4;
    const EVENT_FINISH                 = 5;
    const EVENT_EXCEPTION              = 6;

    protected $kernel;

    public function __construct(HttpKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getKernel()
    {
        return $this->kernel;
    }
}