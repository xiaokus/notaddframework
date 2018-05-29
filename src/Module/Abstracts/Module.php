<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-03-03 15:12
 */
namespace Notadd\Foundation\Module\Abstracts;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Notadd\Foundation\Http\Abstracts\ServiceProvider;
use ReflectionClass;

/**
 * Class Module.
 */
abstract class Module extends ServiceProvider
{
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected static $migrations;

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Module constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->events = $app['events'];
        $this->router = $app['router'];
        static::$migrations = new Collection();
    }

    /**
     * Boot module.
     */
    abstract public function boot();

    /**
     * @return string
     */
    public static function definition()
    {
        $reflection = new ReflectionClass(static::class);

        return $reflection->getNamespaceName() . '\\' . 'Definition';
    }

    /**
     * @param array|string $paths
     */
    public function loadMigrationsFrom($paths)
    {
        static::$migrations = static::$migrations->merge((array)$paths);
        parent::loadMigrationsFrom($paths);
    }

    /**
     * @return array
     */
    public static function migrations()
    {
        return static::$migrations->toArray();
    }

    /**
     * Register module extra providers.
     */
    public function register()
    {
    }
}
