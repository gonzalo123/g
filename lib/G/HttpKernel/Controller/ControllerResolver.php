<?php

namespace G\HttpKernel\Controller;

use G\HttpKernel\Dependency\Resolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\RouteCollection;

class ControllerResolver implements ControllerResolverInterface
{
    private $routes;
    private $dependencyResolver;

    public function __construct(RouteCollection $routes, Resolver $dependencyResolver)
    {
        $this->routes             = $routes;
        $this->dependencyResolver = $dependencyResolver;
    }

    public function getController(Request $request)
    {
        $matcher = $this->getMatcherFromRequest($request);
        $request->attributes->add($matcher->match($request->getPathInfo()));
        $controller = $request->attributes->get('_controller');

        if (false !== strpos($controller, '::')) {
            list($controller, $action) = explode('::', $controller, 2);
        } else {
            $action = $request->attributes->get('_action');
        }

        return [$controller, $action];
    }

    public function getArguments(Request $request, $controller)
    {
        $method         = new \ReflectionMethod($controller[0], $controller[1]);
        $callParameters = [];
        foreach ($method->getParameters() as $param) {
            $parameterName = $param->getName();
            if (isset($param->getClass()->name)) {
                $dependency = $this->dependencyResolver->resolveParamForRequest($request, $param->getClass()->name);
                if (!is_null($dependency)) {
                    $callParameters[$parameterName] = $dependency;
                }
            } else {
                $callParameters[$parameterName] = $request->get($parameterName);
            }
        }

        return $callParameters;
    }

    private function getMatcherFromRequest($request)
    {
        $context = new Routing\RequestContext();
        $context->fromRequest($request);
        $matcher = new Routing\Matcher\UrlMatcher($this->routes, $context);

        return $matcher;
    }
}