<?php

namespace G;

use G\Application\DispatcherTrait;
use G\HttpKernel\Controller\ControllerResolver;
use G\HttpKernel\Dependency\Resolver;
use G\HttpKernel\Event\KernelEvent;
use G\HttpKernel\HttpKernel;
use G\Route\RestExtension;

use Symfony\Component\DependencyInjection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\Routing;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Config\FileLocator;

class Application
{
    use DispatcherTrait;

    protected $dispatcher;
    protected $kernel;

    public function __construct(HttpKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public static function getKernel($conf)
    {
        $defaultConf = [
            'config.path'  => null,
            'view.path'    => null,
            'routes.yml'   => 'routes.yml',
            'services.yml' => 'services.yml',
        ];

        $conf        = array_merge($defaultConf, $conf);
        $fileLocator = new FileLocator($conf['config.path']);

        $container = new DependencyInjection\ContainerBuilder();
        $container->registerExtension(new RestExtension());

        $loader = new DependencyInjection\Loader\YamlFileLoader($container, $fileLocator);
        $loader->load($conf['services.yml']);

        foreach ($conf as $key => $value) {
            $container->setParameter($key, $value);
        }

        $routes = new Routing\RouteCollection();
        $loader = new Routing\Loader\YamlFileLoader($fileLocator);
        $routes->addCollection($loader->load($conf['routes.yml']));

        if ($container->hasExtension('restResources')) {
            $resources = $container->getExtensionConfig('restResources');

            if (isset($resources[0])) {
                foreach ($resources[0] as $routeName => $routeDefinition) {
                    $methods = [
                        'getAction'    => ['GET', '/{id}'],
                        'getAllAction' => ['GET', ''],
                        'saveAction'   => ['POST', '/{id}'],
                        'addAction'    => ['POST', ''],
                        'deleteAction' => ['DELETE', '/{id}'],
                    ];

                    foreach ($methods as $methodAction => $methodConf) {
                        $routes->add(
                            "{$routeName}.{$methodAction}", new Routing\Route($routeDefinition['path'] . $methodConf[1], [
                                '_controller' => $routeDefinition['class'] . '::' . $methodAction,
                            ], [], [], '', [], [$methodConf[0]])
                        );
                    }
                }
            }
        }

        $dependencyResolver = new Resolver($container);
        $controllerResolver = new ControllerResolver($routes, $dependencyResolver);
        $eventDispatcher    = new EventDispatcher();
        $eventDispatcher->addListener(KernelEvent::EVENT_CONTROLLER_SECURITY, function (KernelEvent $event) use ($container) {
                $security = $container->getParameter('security');
                $obj = new $security['class']($event->getKernel()->getRequest());
            }, 1);
        $eventDispatcher->addListener(KernelEvent::EVENT_LOGIN, function (KernelEvent $event) use ($container) {
                $event->getKernel()->setController(explode('::', $container->getParameter('security')['login'], 2));
            }, 1);
        return new HttpKernel($eventDispatcher, $controllerResolver);
    }

    public static function factory($conf)
    {
        return new Application(self::getKernel($conf));
    }

    public function run(Request $request = null)
    {
        if (is_null($request)) {
            $request = Request::createFromGlobals();
        }

        $response = $this->handle($request);
        $response->send();
    }

    public function handle(Request $request)
    {
        try {
            $response = $this->kernel->handle($request);
            if ($this->kernel instanceof TerminableInterface) {
                $this->kernel->terminate($request, $response);
            }
        } catch (Routing\Exception\ResourceNotFoundException $e) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $e) {
            $response = new Response('An error occurred', 500);
        }

        return $response;
    }
}