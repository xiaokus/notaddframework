<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime      2016-10-20 19:41
 */
namespace Notadd\Foundation;

use Closure;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Log\LogServiceProvider;
use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\ViewServiceProvider;
use Notadd\Foundation\Database\DatabaseServiceProvider;
use Notadd\Foundation\Extension\ExtensionServiceProvider;
use Notadd\Foundation\Http\Bootstraps\LoadEnvironmentVariables;
use Notadd\Foundation\Http\Events\BootstrapBooted;
use Notadd\Foundation\Module\ModuleServiceProvider;
use Notadd\Foundation\Routing\RoutingServiceProvider;
use Notadd\Foundation\Setting\SettingServiceProvider;
use Notadd\Foundation\Translation\Events\LocaleUpdated;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class Application.
 */
class Application extends Container implements ApplicationContract, HttpKernelInterface
{
    /**
     * The Notadd Framework version.
     *
     * @var string
     */
    const VERSION = '0.16.1';

    /**
     * The base path for the Notadd installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Indicates if the application has been bootstrapped before.
     *
     * @var bool
     */
    protected $hasBeenBootstrapped = false;

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * The array of booting callbacks.
     *
     * @var array
     */
    protected $bootingCallbacks = [];

    /**
     * The array of booted callbacks.
     *
     * @var array
     */
    protected $bootedCallbacks = [];

    /**
     * The array of terminating callbacks.
     *
     * @var array
     */
    protected $terminatingCallbacks = [];

    /**
     * All of the registered service providers.
     *
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * The names of the loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * The deferred services and their providers.
     *
     * @var array
     */
    protected $deferredServices = [];

    /**
     * A custom callback used to configure Monolog.
     *
     * @var callable|null
     */
    protected $monologConfigurator;

    /**
     * The custom database path defined by the developer.
     *
     * @var string
     */
    protected $databasePath;

    /**
     * @var string
     */
    protected $publicPath;

    /**
     * @var string
     */
    protected $resourcePath;

    /**
     * The custom storage path defined by the developer.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * The custom environment path defined by the developer.
     *
     * @var string
     */
    protected $environmentPath;

    /**
     * The environment file to load during bootstrapping.
     *
     * @var string
     */
    protected $environmentFile = 'environment.yaml';

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace = null;

    /**
     * Application constructor.
     *
     * @param null $basePath
     */
    public function __construct($basePath = null)
    {
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
        $this->registerCoreContainerAliases();
        if ($basePath) {
            $this->setBasePath(realpath($basePath));
        }
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

    /**
     * Register the basic bindings into the container.
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
    }

    /**
     * Register all of the base service providers.
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));
        $this->register(new FilesystemServiceProvider($this));
        $this->register(new LogServiceProvider($this));
        $this->register(new RoutingServiceProvider($this));
        $this->register(new ViewServiceProvider($this));
        $this->register(new RedisServiceProvider($this));
        $this->register(new CacheServiceProvider($this));
        $this->register(new DatabaseServiceProvider($this));
        $this->register(new ExtensionServiceProvider($this));
        $this->register(new ModuleServiceProvider($this));
        $this->register(new SettingServiceProvider($this));
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @param array $bootstrappers
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function bootstrapWith(array $bootstrappers)
    {
        $this->hasBeenBootstrapped = true;
        foreach ($bootstrappers as $bootstrapper) {
            $this['events']->dispatch('bootstrapping: ' . $bootstrapper, [$this]);
            $this->make($bootstrapper)->bootstrap();
            $this['events']->dispatch('bootstrapped: ' . $bootstrapper, [$this]);
        }
        $this['events']->dispatch(new BootstrapBooted());
    }

    /**
     * Register a callback to run after loading the environment.
     *
     * @param \Closure $callback
     */
    public function afterLoadingEnvironment(Closure $callback)
    {
        return $this->afterBootstrapping(LoadEnvironmentVariables::class, $callback);
    }

    /**
     * Register a callback to run before a bootstrapper.
     *
     * @param          $bootstrapper
     * @param \Closure $callback
     */
    public function beforeBootstrapping($bootstrapper, Closure $callback)
    {
        $this['events']->listen('bootstrapping: ' . $bootstrapper, $callback);
    }

    /**
     * Register a callback to run after a bootstrapper.
     *
     * @param          $bootstrapper
     * @param \Closure $callback
     */
    public function afterBootstrapping($bootstrapper, Closure $callback)
    {
        $this['events']->listen('bootstrapped: ' . $bootstrapper, $callback);
    }

    /**
     * Determine if the application has been bootstrapped before.
     *
     * @return bool
     */
    public function hasBeenBootstrapped()
    {
        return $this->hasBeenBootstrapped;
    }

    /**
     * Set the base path for the application.
     *
     * @param $basePath
     *
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');
        $this->bindPathsInContainer();

        return $this;
    }

    /**
     * Bind all of the application paths in the container.
     */
    protected function bindPathsInContainer()
    {
        $this->instance('path', $this->path());
        $this->instance('path.addon', $this->addonPath());
        $this->instance('path.base', $this->basePath());
        $this->instance('path.bootstrap', $this->bootstrapPath());
        $this->instance('path.config', $this->configPath());
        $this->instance('path.database', $this->databasePath());
        $this->instance('path.extension', $this->extensionPath());
        $this->instance('path.framework', $this->frameworkPath());
        $this->instance('path.lang', $this->langPath());
        $this->instance('path.module', $this->modulePath());
        $this->instance('path.public', $this->publicPath());
        $this->instance('path.resources', $this->resourcePath());
        $this->instance('path.static', $this->staticPath());
        $this->instance('path.storage', $this->storagePath());
    }

    /**
     * Get the path to the application directory, alias of base path.
     *
     * @param string $path
     *
     * @return string
     */
    public function path($path = '')
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the base path of the Notadd installation.
     *
     * @param string $path
     *
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the bootstrap directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function bootstrapPath($path = '')
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'bootstrap' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param string $path
     *
     * @return string
     */
    public function configPath($path = '')
    {
        return realpath(__DIR__ . '/../configurations') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the database directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function databasePath($path = '')
    {
        return $this->databasePath ?: $this->basePath . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'databases' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Set the database directory.
     *
     * @param $path
     *
     * @return $this
     */
    public function useDatabasePath($path)
    {
        $this->databasePath = $path;
        $this->instance('path.database', $path);

        return $this;
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath()
    {
        return $this->resourcePath() . DIRECTORY_SEPARATOR . 'translations';
    }

    /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function publicPath()
    {
        return $this->publicPath ?: $this->basePath . DIRECTORY_SEPARATOR . 'public';
    }

    /**
     * Set the path to the public / web directory.
     *
     * @param $path
     *
     * @return $this
     */
    public function usePublicPath($path)
    {
        $this->publicPath = $path;
        $this->instance('path.public', $path);

        return $this;
    }

    /**
     * Get the path to the storage directory.
     *
     * @return string
     */
    public function storagePath()
    {
        return $this->storagePath ?: $this->basePath . DIRECTORY_SEPARATOR . 'storage';
    }

    /**
     * Set the storage directory.
     *
     * @param $path
     *
     * @return $this
     */
    public function useStoragePath($path)
    {
        $this->storagePath = $path;
        $this->instance('path.storage', $path);

        return $this;
    }

    /**
     * Get the path to the resources directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function resourcePath($path = '')
    {
        return $this->resourcePath ?: realpath(__DIR__ . '/../resources') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Set the resource directory.
     *
     * @param $path
     *
     * @return $this
     */
    public function useResourcePath($path)
    {
        $this->resourcePath = $path;
        $this->instance('path.resources', $path);

        return $this;
    }

    /**
     * Get the path to the environment file directory.
     *
     * @return string
     */
    public function environmentPath()
    {
        return $this->environmentPath ?: $this->storagePath() . DIRECTORY_SEPARATOR . 'environments';
    }

    /**
     * Set the directory for the environment file.
     *
     * @param $path
     *
     * @return $this
     */
    public function useEnvironmentPath($path)
    {
        $this->environmentPath = $path;

        return $this;
    }

    /**
     * Set the environment file to be loaded during bootstrapping.
     *
     * @param $file
     *
     * @return $this
     */
    public function loadEnvironmentFrom($file)
    {
        $this->environmentFile = $file;

        return $this;
    }

    /**
     * Get the environment file the application is using.
     *
     * @return string
     */
    public function environmentFile()
    {
        return $this->environmentFile ?: 'environment.yaml';
    }

    /**
     * Get the fully qualified path to the environment file.
     *
     * @return string
     */
    public function environmentFilePath()
    {
        return $this->environmentPath() . DIRECTORY_SEPARATOR . $this->environmentFile();
    }

    /**
     * Get or check the current application environment.
     *
     * @return bool|string
     */
    public function environment()
    {
        if (func_num_args() > 0) {
            $patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();
            foreach ($patterns as $pattern) {
                if (Str::is($pattern, $this['env'])) {
                    return true;
                }
            }

            return false;
        }

        return $this['env'];
    }

    /**
     * Determine if application is in local environment.
     *
     * @return bool
     */
    public function isLocal()
    {
        return $this['env'] == 'local';
    }

    /**
     * Detect the application's current environment.
     *
     * @param \Closure $callback
     *
     * @return string
     */
    public function detectEnvironment(Closure $callback)
    {
        $args = $_SERVER['argv'] ?? null;

        return $this['env'] = (new EnvironmentDetector())->detect($callback, $args);
    }

    /**
     * Determine if we are running in the console.
     *
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() == 'cli';
    }

    /**
     * Determine if we are running unit tests.
     *
     * @return bool
     */
    public function runningUnitTests()
    {
        return $this['env'] == 'testing';
    }

    /**
     * Register all of the configured providers.
     */
    public function registerConfiguredProviders()
    {
        $manifestPath = $this->getCachedServicesPath();
        (new ProviderRepository($this, $this['files'], $manifestPath))->load($this->config['app.providers']);
    }

    /**
     * Register a service provider with the application.
     *
     * @param \Illuminate\Support\ServiceProvider|string $provider
     * @param array                                      $options
     * @param bool                                       $force
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, $options = [], $force = false)
    {
        if (($registered = $this->getProvider($provider)) && !$force) {
            return $registered;
        }
        if (is_string($provider)) {
            $provider = $this->resolveProviderClass($provider);
        }
        if (method_exists($provider, 'register')) {
            $provider->register();
        }
        foreach ($options as $key => $value) {
            $this[$key] = $value;
        }
        $this->markAsRegistered($provider);
        if ($this->booted) {
            $this->bootProvider($provider);
        }

        return $provider;
    }

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param \Illuminate\Support\ServiceProvider|string $provider
     *
     * @return \Illuminate\Support\ServiceProvider|null
     */
    public function getProvider($provider)
    {
        $name = is_string($provider) ? $provider : get_class($provider);

        return Arr::first($this->serviceProviders, function ($value) use ($name) {
            return $value instanceof $name;
        });
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param string $provider
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function resolveProviderClass($provider)
    {
        return new $provider($this);
    }

    /**
     * Mark the given provider as registered.
     *
     * @param \Illuminate\Support\ServiceProvider $provider
     */
    protected function markAsRegistered($provider)
    {
        $this['events']->dispatch($class = get_class($provider), [$provider]);
        $this->serviceProviders[] = $provider;
        $this->loadedProviders[$class] = true;
    }

    /**
     * Load and boot all of the remaining deferred providers.
     */
    public function loadDeferredProviders()
    {
        foreach ($this->deferredServices as $service => $provider) {
            $this->loadDeferredProvider($service);
        }
        $this->deferredServices = [];
    }

    /**
     * Load the provider for a deferred service.
     *
     * @param $service
     */
    public function loadDeferredProvider($service)
    {
        if (!isset($this->deferredServices[$service])) {
            return;
        }
        $provider = $this->deferredServices[$service];
        if (!isset($this->loadedProviders[$provider])) {
            $this->registerDeferredProvider($provider, $service);
        }
    }

    /**
     * Register a deferred provider and service.
     *
     * @param string $provider
     * @param null   $service
     */
    public function registerDeferredProvider($provider, $service = null)
    {
        if ($service) {
            unset($this->deferredServices[$service]);
        }
        $this->register($instance = new $provider($this));
        if (!$this->booted) {
            $this->booting(function () use ($instance) {
                $this->bootProvider($instance);
            });
        }
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array  $parameters
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function make($abstract, array $parameters = [])
    {
        $abstract = $this->getAlias($abstract);
        if (isset($this->deferredServices[$abstract]) && !isset($this->instances[$abstract])) {
            $this->loadDeferredProvider($abstract);
        }

        return parent::make($abstract, $parameters);
    }

    /**
     * Check a instance is bound or not.
     *
     * @param string $abstract
     *
     * @return bool
     */
    public function bound($abstract)
    {
        return isset($this->deferredServices[$abstract]) || parent::bound($abstract);
    }

    /**
     * Is Application Booted.
     *
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }

    /**
     * Boot Application.
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }
        $this->fireAppCallbacks($this->bootingCallbacks);
        array_walk($this->serviceProviders, function ($p) {
            $this->bootProvider($p);
        });
        $this->booted = true;
        $this->fireAppCallbacks($this->bootedCallbacks);
    }

    /**
     * Boot the given service provider.
     *
     * @param \Illuminate\Support\ServiceProvider $provider
     *
     * @return mixed
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            return $this->call([
                $provider,
                'boot',
            ]);
        }
    }

    /**
     * Register a new boot listener.
     *
     * @param mixed $callback
     */
    public function booting($callback)
    {
        $this->bootingCallbacks[] = $callback;
    }

    /**
     * Register a new "booted" listener.
     *
     * @param mixed $callback
     */
    public function booted($callback)
    {
        $this->bootedCallbacks[] = $callback;
        if ($this->isBooted()) {
            $this->fireAppCallbacks([$callback]);
        }
    }

    /**
     * Call the booting callbacks for the application.
     *
     * @param array $callbacks
     */
    protected function fireAppCallbacks(array $callbacks)
    {
        foreach ($callbacks as $callback) {
            call_user_func($callback, $this);
        }
    }

    /**
     * Handle a request.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int                                       $type
     * @param bool                                      $catch
     *
     * @return mixed
     */
    public function handle(SymfonyRequest $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $laravel = Request::createFromBase($request);
        $laravel->headers->replace($request->headers->all());

        return $this['Illuminate\Contracts\Http\Kernel']->handle($laravel);
    }

    /**
     * Determine if middleware has been disabled for the application.
     *
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function shouldSkipMiddleware()
    {
        return $this->bound('middleware.disable') && $this->make('middleware.disable') === true;
    }

    /**
     * Determine if the application configuration is cached.
     *
     * @return bool
     */
    public function configurationIsCached()
    {
        return file_exists($this->getCachedConfigPath());
    }

    /**
     * Get the path to the configuration cache file.
     *
     * @return string
     */
    public function getCachedConfigPath()
    {
        return $this->storagePath() . '/bootstraps/configurations.php';
    }

    /**
     * Determine if the application routes are cached.
     *
     * @return mixed
     */
    public function routesAreCached()
    {
        return $this['files']->exists($this->getCachedRoutesPath());
    }

    /**
     * Get the path to the routes cache file.
     *
     * @return string
     */
    public function getCachedRoutesPath()
    {
        return $this->storagePath() . '/bootstraps/routes.php';
    }

    /**
     * Get the path to the cached services.php file.
     *
     * @return string
     */
    public function getCachedServicesPath()
    {
        return $this->storagePath() . '/bootstraps/services.php';
    }

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function isDownForMaintenance()
    {
        return file_exists($this->storagePath() . '/bootstraps/down');
    }

    /**
     * Throw an HttpException with the given data.
     *
     * @param int    $code
     * @param string $message
     * @param array  $headers
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function abort($code, $message = '', array $headers = [])
    {
        if ($code == 404) {
            throw new NotFoundHttpException($message);
        }
        throw new HttpException($code, $message, null, $headers);
    }

    /**
     * Register a terminating callback with the application.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function terminating(Closure $callback)
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    /**
     * Terminate the application.
     */
    public function terminate()
    {
        foreach ($this->terminatingCallbacks as $terminating) {
            $this->call($terminating);
        }
    }

    /**
     * Get the service providers that have been loaded.
     *
     * @return array
     */
    public function getLoadedProviders()
    {
        return $this->loadedProviders;
    }

    /**
     * Get the application's deferred services.
     *
     * @return array
     */
    public function getDeferredServices()
    {
        return $this->deferredServices;
    }

    /**
     * Set the application's deferred services.
     *
     * @param array $services
     */
    public function setDeferredServices(array $services)
    {
        $this->deferredServices = $services;
    }

    /**
     * Add an array of services to the application's deferred services.
     *
     * @param array $services
     */
    public function addDeferredServices(array $services)
    {
        $this->deferredServices = array_merge($this->deferredServices, $services);
    }

    /**
     * Determine if the given service is a deferred service.
     *
     * @param string $service
     *
     * @return bool
     */
    public function isDeferredService($service)
    {
        return isset($this->deferredServices[$service]);
    }

    /**
     * Define a callback to be used to configure Monolog.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function configureMonologUsing(callable $callback)
    {
        $this->monologConfigurator = $callback;

        return $this;
    }

    /**
     * Determine if the application has a custom Monolog configurator.
     *
     * @return bool
     */
    public function hasMonologConfigurator()
    {
        return !is_null($this->monologConfigurator);
    }

    /**
     * Get the custom Monolog configurator for the application.
     *
     * @return callable
     */
    public function getMonologConfigurator()
    {
        return $this->monologConfigurator;
    }

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this['config']->get('app.locale');
    }

    /**
     * Set the current application locale.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this['config']->set('app.locale', $locale);
        $this['translator']->setLocale($locale);
        $this['events']->dispatch(LocaleUpdated::class, [$locale]);
    }

    /**
     * Determine if application locale is the given locale.
     *
     * @param string $locale
     *
     * @return bool
     */
    public function isLocale($locale)
    {
        return $this->getLocale() == $locale;
    }

    /**
     * Register the core class aliases in the container.
     */
    public function registerCoreContainerAliases()
    {
        $data = [
            'administration'            => [\Notadd\Foundation\Administration\AdministrationManager::class],
            'addon'                     => [\Notadd\Foundation\Addon\AddonManager::class],
            'app'                       => [
                \Illuminate\Contracts\Container\Container::class,
                \Illuminate\Contracts\Foundation\Application::class,
                \Notadd\Foundation\Application::class,
            ],
            'auth'                      => [
                \Illuminate\Auth\AuthManager::class,
                \Illuminate\Contracts\Auth\Factory::class,
            ],
            'auth.driver'               => [\Illuminate\Contracts\Auth\Guard::class],
            'blade.compiler'            => [\Illuminate\View\Compilers\BladeCompiler::class],
            'cache'                     => [
                \Illuminate\Cache\CacheManager::class,
                \Illuminate\Contracts\Cache\Factory::class,
            ],
            'cache.store'               => [
                \Illuminate\Cache\Repository::class,
                \Illuminate\Contracts\Cache\Repository::class,
            ],
            'config'                    => [
                \Illuminate\Contracts\Config\Repository::class,
                \Illuminate\Config\Repository::class,
            ],
            'cookie'                    => [
                \Illuminate\Cookie\CookieJar::class,
                \Illuminate\Contracts\Cookie\Factory::class,
                \Illuminate\Contracts\Cookie\QueueingFactory::class,
            ],
            'db'                        => [\Illuminate\Database\DatabaseManager::class],
            'db.connection'             => [
                \Illuminate\Database\Connection::class,
                \Illuminate\Database\ConnectionInterface::class,
            ],
            'encrypter'                 => [
                \Illuminate\Encryption\Encrypter::class,
                \Illuminate\Contracts\Encryption\Encrypter::class,
            ],
            'events'                    => [
                \Illuminate\Events\Dispatcher::class,
                \Illuminate\Contracts\Events\Dispatcher::class,
            ],
            'files'                     => [\Illuminate\Filesystem\Filesystem::class],
            'filesystem'                => [
                \Illuminate\Filesystem\FilesystemManager::class,
                \Illuminate\Contracts\Filesystem\Factory::class,
            ],
            'filesystem.disk'           => [\Illuminate\Contracts\Filesystem\Filesystem::class],
            'filesystem.cloud'          => [\Illuminate\Contracts\Filesystem\Cloud::class],
            'flow'                      => [\Notadd\Foundation\Flow\FlowManager::class],
            'graphql'                   => [\Notadd\Foundation\GraphQL\GraphQLManager::class],
            'hash'                      => [\Illuminate\Contracts\Hashing\Hasher::class],
            'images'                    => [\Notadd\Foundation\Image\ImageManager::class],
            'jwt'                       => [\Notadd\Foundation\JWTAuth\JWT::class],
            'jwt.auth'                  => [\Notadd\Foundation\JWTAuth\JWTAuth::class],
            'jwt.blacklist'             => [\Notadd\Foundation\JWTAuth\Blacklist::class],
            'jwt.claim.factory'         => [\Notadd\Foundation\JWTAuth\Claims\Factory::class],
            'jwt.manager'               => [\Notadd\Foundation\JWTAuth\Manager::class],
            'jwt.payload.factory'       => [\Notadd\Foundation\JWTAuth\Factory::class],
            'jwt.provider.auth'         => [\Notadd\Foundation\JWTAuth\Contracts\Providers\Auth::class],
            'jwt.provider.jwt'          => [\Notadd\Foundation\JWTAuth\Contracts\Providers\JWT::class],
            'jwt.provider.storage'      => [\Notadd\Foundation\JWTAuth\Contracts\Providers\Storage::class],
            'jwt.validators.payload'    => [\Notadd\Foundation\JWTAuth\Validators\PayloadValidator::class],
            'log'                       => [
                \Illuminate\Log\Writer::class,
                \Illuminate\Contracts\Logging\Log::class,
                \Psr\Log\LoggerInterface::class,
            ],
            'mailer'                    => [
                \Illuminate\Mail\Mailer::class,
                \Illuminate\Contracts\Mail\Mailer::class,
                \Illuminate\Contracts\Mail\MailQueue::class,
            ],
            'member'                    => [\Notadd\Foundation\Member\MemberManagement::class],
            'module'                    => [\Notadd\Foundation\Module\ModuleManager::class],
            'permission'                => [\Notadd\Foundation\Permission\PermissionManager::class],
            'queue'                     => [
                \Illuminate\Queue\QueueManager::class,
                \Illuminate\Contracts\Queue\Factory::class,
                \Illuminate\Contracts\Queue\Monitor::class,
            ],
            'queue.connection'          => [\Illuminate\Contracts\Queue\Queue::class],
            'queue.failer'              => [\Illuminate\Queue\Failed\FailedJobProviderInterface::class],
            'redirect'                  => [
                \Illuminate\Routing\Redirector::class,
                \Notadd\Foundation\Routing\Redirector::class,
            ],
            'redis'                     => [
                \Illuminate\Redis\RedisManager::class,
                \Illuminate\Contracts\Redis\Factory::class,
            ],
            'request'                   => [
                \Illuminate\Http\Request::class,
                \Symfony\Component\HttpFoundation\Request::class,
            ],
            'router'                    => [
                \Illuminate\Routing\Router::class,
                \Illuminate\Contracts\Routing\Registrar::class,
                \Notadd\Foundation\Routing\Router::class,
            ],
            'searchengine.optimization' => [\Notadd\Foundation\SearchEngine\Optimization::class],
            'session'                   => [\Illuminate\Session\SessionManager::class],
            'session.store'             => [
                \Illuminate\Session\Store::class,
                \Symfony\Component\HttpFoundation\Session\SessionInterface::class,
            ],
            'setting'                   => [\Notadd\Foundation\Setting\Contracts\SettingsRepository::class],
            'theme'                     => [\Notadd\Foundation\Theme\ThemeManager::class],
            'translator'                => [
                \Illuminate\Translation\Translator::class,
                \Illuminate\Contracts\Translation\Translator::class,
                \Notadd\Foundation\Translation\Translator::class,
            ],
            'url'                       => [
                \Illuminate\Routing\UrlGenerator::class,
                \Illuminate\Contracts\Routing\UrlGenerator::class,
            ],
            'validator'                 => [
                \Illuminate\Validation\Factory::class,
                \Illuminate\Contracts\Validation\Factory::class,
            ],
            'view'                      => [
                \Illuminate\View\Factory::class,
                \Illuminate\Contracts\View\Factory::class,
            ],
        ];
        foreach ($data as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }

    /**
     * Flush the container of all bindings and resolved instances.
     */
    public function flush()
    {
        parent::flush();
        $this->buildStack = [];
        $this->loadedProviders = [];
        $this->bootedCallbacks = [];
        $this->bootingCallbacks = [];
        $this->deferredServices = [];
        $this->reboundCallbacks = [];
        $this->serviceProviders = [];
        $this->resolvingCallbacks = [];
        $this->afterResolvingCallbacks = [];
        $this->globalResolvingCallbacks = [];
    }

    /**
     * Get the application namespace.
     *
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getNamespace()
    {
        if (!is_null($this->namespace)) {
            return $this->namespace;
        }
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        foreach ((array)data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array)$path as $pathChoice) {
                if (realpath(app_path()) == realpath(base_path() . '/' . $pathChoice)) {
                    return $this->namespace = $namespace;
                }
            }
        }
        throw new RuntimeException('Unable to detect application namespace.');
    }

    /**
     * Get application installation status.
     *
     * @return bool
     */
    public function isInstalled()
    {
        if ($this->bound('installed')) {
            return true;
        } else {
            if (!file_exists($this->storagePath() . DIRECTORY_SEPARATOR . 'installed')) {
                return false;
            }
            $this->instance('installed', true);

            return true;
        }
    }

    /**
     * Get the path to the application statics files.
     *
     * @param string $path
     *
     * @return string
     */
    public function staticPath($path = '')
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'public' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the cached packages.php file.
     *
     * @return string
     */
    public function getCachedPackagesPath()
    {
        return '';
    }

    /**
     * Get the path to the application framework files.
     *
     * @param string $path
     *
     * @return string
     */
    public function frameworkPath($path = '')
    {
        return __DIR__ . '/../../framework' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * @return string
     */
    public function modulePath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'modules';
    }

    /**
     * @return string
     */
    public function addonPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'addons';
    }

    /**
     * @return string
     */
    public function extensionPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'extensions';
    }
	
	/**
	 * Gets the additional extension components of the system.
	 * 
	 * @return array
	 */
	public function getAdditionalExtension(){
		$extends = [];
		$operatingSystem = php_uname('s');
		
		if(stristr($operatingSystem, 'windows') !== false){
			$extends[] = 'com_dotnet';
		}
		
		return $extends;
	}
}
