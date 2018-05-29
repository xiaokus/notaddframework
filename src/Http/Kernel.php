<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-20 20:04
 */
namespace Notadd\Foundation\Http;

use Exception;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel as KernelContract;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Pipeline;
use Illuminate\Routing\Router;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Notadd\Foundation\Http\Bootstraps\LoadDetection;
use Notadd\Foundation\Http\Bootstraps\LoadAddon;
use Notadd\Foundation\Http\Bootstraps\LoadExtension;
use Notadd\Foundation\Http\Bootstraps\LoadGraphQL;
use Notadd\Foundation\Http\Bootstraps\LoadModule;
use Notadd\Foundation\Http\Bootstraps\LoadProviders;
use Notadd\Foundation\Http\Bootstraps\ConfigureLogging;
use Notadd\Foundation\Http\Bootstraps\LoadEnvironmentVariables;
use Notadd\Foundation\Http\Bootstraps\HandleExceptions;
use Notadd\Foundation\Http\Bootstraps\LoadConfiguration;
use Notadd\Foundation\Http\Bootstraps\RegisterFacades;
use Notadd\Foundation\Http\Bootstraps\RegisterFlow;
use Notadd\Foundation\Http\Bootstraps\RegisterRouter;
use Notadd\Foundation\Http\Middlewares\Authenticate;
use Notadd\Foundation\Http\Middlewares\CheckForCloseMode;
use Notadd\Foundation\Http\Events\RequestHandled;
use Notadd\Foundation\Http\Middlewares\CheckForMaintenanceMode;
use Notadd\Foundation\Http\Middlewares\EnableCrossRequest;
use Notadd\Foundation\Http\Middlewares\RedirectIfAuthenticated;
use Notadd\Foundation\Http\Middlewares\ShareMessagesFromSession;
use Notadd\Foundation\Http\Middlewares\VerifyCsrfToken;
use Notadd\Foundation\Permission\Middlewares\Permission;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;

/**
 * Class Kernel.
 */
class Kernel implements KernelContract
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application|\Notadd\Foundation\Application
     */
    protected $application;

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * @var array
     */
    protected $bootstrappers = [
        LoadEnvironmentVariables::class,
        LoadConfiguration::class,
        ConfigureLogging::class,
        HandleExceptions::class,
        LoadExtension::class,
        LoadModule::class,
        LoadProviders::class,
        LoadAddon::class,
        RegisterFacades::class,
        LoadDetection::class,
        LoadGraphQL::class,
        RegisterRouter::class,
        RegisterFlow::class,
    ];

    /**
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
    ];

    /**
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            CheckForCloseMode::class,
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            ShareMessagesFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],
        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'cross'      => EnableCrossRequest::class,
        'bindings'   => SubstituteBindings::class,
        'can'        => Authorize::class,
        'guest'      => RedirectIfAuthenticated::class,
        'permission' => Permission::class,
        'throttle'   => ThrottleRequests::class,
    ];

    /**
     * @var array
     */
    protected $middlewarePriority = [
        StartSession::class,
        ShareErrorsFromSession::class,
        Authenticate::class,
        SubstituteBindings::class,
        Authorize::class,
    ];

    /**
     * Kernel constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application|\Notadd\Foundation\Application $app
     * @param \Illuminate\Routing\Router                                                  $router
     */
    public function __construct(Application $app, Router $router)
    {
        $this->application = $app;
        $this->router = $router;
        $router->middlewarePriority = $this->middlewarePriority;
        foreach ($this->middlewareGroups as $key => $middleware) {
            $router->middlewareGroup($key, $middleware);
        }
        foreach ($this->routeMiddleware as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }

    /**
     * Handle an incoming HTTP request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handle($request)
    {
        try {
            $request->enableHttpMethodParameterOverride();
            $response = $this->sendRequestThroughRouter($request);
        } catch (Exception $e) {
            $this->reportException($e);
            $response = $this->renderException($request, $e);
        } catch (Throwable $e) {
            $this->reportException($e = new FatalThrowableError($e));
            $response = $this->renderException($request, $e);
        }
        $this->application['events']->dispatch(RequestHandled::class, [
            $request,
            $response,
        ]);

        return $response;
    }

    /**
     * Send the given request through the middleware / router.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function sendRequestThroughRouter($request)
    {
        $this->application->instance('request', $request);
        Facade::clearResolvedInstance('request');
        $this->bootstrap();

        return (new Pipeline($this->application))->send($request)->through($this->application->shouldSkipMiddleware() ? [] : $this->middleware)->then($this->dispatchToRouter());
    }

    /**
     * Call the terminate method on any terminable middleware.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Illuminate\Http\Response $response
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function terminate($request, $response)
    {
        $middlewares = $this->application->shouldSkipMiddleware() ? [] : array_merge($this->gatherRouteMiddleware($request),
            $this->middleware);
        foreach ($middlewares as $middleware) {
            if (!is_string($middleware)) {
                continue;
            }
            list($name, $parameters) = $this->parseMiddleware($middleware);
            $instance = $this->application->make($name);
            if (method_exists($instance, 'terminate')) {
                $instance->terminate($request, $response);
            }
        }
        $this->application->terminate();
    }

    /**
     * Gather the route middleware for the given request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function gatherRouteMiddleware($request)
    {
        if ($route = $request->route()) {
            return $this->router->gatherRouteMiddleware($route);
        }

        return [];
    }

    /**
     * Parse a middleware string to get the name and parameters.
     *
     * @param string $middleware
     *
     * @return array
     */
    protected function parseMiddleware($middleware)
    {
        list($name, $parameters) = array_pad(explode(':', $middleware, 2), 2, []);
        if (is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }

        return [
            $name,
            $parameters,
        ];
    }

    /**
     * Add a new middleware to beginning of the stack if it does not already exist.
     *
     * @param string $middleware
     *
     * @return $this
     */
    public function prependMiddleware($middleware)
    {
        if (array_search($middleware, $this->middleware) === false) {
            array_unshift($this->middleware, $middleware);
        }

        return $this;
    }

    /**
     * Add a new middleware to end of the stack if it does not already exist.
     *
     * @param string $middleware
     *
     * @return $this
     */
    public function pushMiddleware($middleware)
    {
        if (array_search($middleware, $this->middleware) === false) {
            $this->middleware[] = $middleware;
        }

        return $this;
    }

    /**
     * Bootstrap the application for HTTP requests.
     */
    public function bootstrap()
    {
        if (!$this->application->hasBeenBootstrapped()) {
            $this->application->bootstrapWith($this->bootstrappers());
        }
    }

    /**
     * Get the route dispatcher callback.
     *
     * @return \Closure
     */
    protected function dispatchToRouter()
    {
        return function ($request) {
            $this->application->instance('request', $request);

            return $this->router->dispatch($request);
        };
    }

    /**
     * Determine if the kernel has a given middleware.
     *
     * @param $middleware
     *
     * @return bool
     */
    public function hasMiddleware($middleware)
    {
        return in_array($middleware, $this->middleware);
    }

    /**
     * Get the bootstrap classes for the application.
     *
     * @return array
     */
    protected function bootstrappers()
    {
        return $this->bootstrappers;
    }

    /**
     * Report the exception to the exception handler.
     *
     * @param \Exception $e
     */
    protected function reportException(Exception $e)
    {
        $this->application[ExceptionHandler::class]->report($e);
    }

    /**
     * Render the exception to a response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderException($request, Exception $e)
    {
        return $this->application[ExceptionHandler::class]->render($request, $e);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function getApplication()
    {
        return $this->application;
    }
}
