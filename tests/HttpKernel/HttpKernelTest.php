<?php

use G\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\Request;

include_once __DIR__ . '/../fixtures/AppController.php';

class HttpKernelTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle()
    {
        $httpKernel = $this->getKernelFromController(["AppController", "homeAction"]);

        $request = new Request();
        $request->setMethod('GET');

        $response = $httpKernel->handle($request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals('AppController::home', $response->getContent());
    }

    private function getKernelFromController($controller)
    {
        $dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();
        $controllerResolver = $this->getMockBuilder('G\HttpKernel\Controller\ControllerResolver')
            ->disableOriginalConstructor()
            ->getMock();

        $controllerResolver->expects($this->any())->method('getController')->will($this->returnValue($controller));
        $controllerResolver->expects($this->any())->method('getArguments')->will($this->returnValue([]));

        return new HttpKernel($dispatcher, $controllerResolver);
    }
}