<?php

use G\HttpKernel\HttpKernel;
use G\HttpKernel\Event\KernelEvent;

class KernelEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetKernel()
    {
        $kernel = $this->getMockBuilder('G\HttpKernel\HttpKernel')
            ->disableOriginalConstructor()
            ->getMock();

        $kernelEvent = new KernelEvent($kernel);

        $this->assertInstanceOf('G\HttpKernel\HttpKernel', $kernelEvent->getKernel());
    }
}