<?php

namespace G\HttpKernel\Dependency;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

class Resolver
{
    private $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function resolveParamForRequest(Request $request, $param)
    {
        if ($param == 'Symfony\Component\HttpFoundation\Request') {
            return $request;
        }

        if ($this->container->has($param)) {
            return $this->container->get($param);
        }

        throw new \Exception("Dependency '{$param}' not found");
    }
}