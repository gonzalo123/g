<?php

use G\HttpKernel\Dependency\Resolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

class ResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRequest()
    {
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects($this->any())->method('get')->will($this->returnValue('hi'));
        $container->expects($this->any())->method('has')->will($this->returnValue(true));

        $request = new Request();
        $resolver = new Resolver($container);

        $this->assertEquals($request, $resolver->resolveParamForRequest($request, 'Symfony\Component\HttpFoundation\Request'));
        $this->assertEquals('hi', $resolver->resolveParamForRequest($request, 'another\namespace'));
    }
}