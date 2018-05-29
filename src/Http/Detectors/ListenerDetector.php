<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-14 20:36
 */
namespace Notadd\Foundation\Http\Detectors;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Notadd\Foundation\Http\Contracts\Detector;

/**
 * Class ListenerDetector.
 */
class ListenerDetector implements Detector
{
    /**
     * @var \Illuminate\Container\Container|\Notadd\Foundation\Application
     */
    protected $container;

    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $event;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $file;

    /**
     * ListenerDetector constructor.
     *
     * @param \Illuminate\Container\Container|  $container
     * @param \Illuminate\Events\Dispatcher     $event
     * @param \Illuminate\Filesystem\Filesystem $file
     */
    public function __construct(Container $container, Dispatcher $event, Filesystem $file)
    {
        $this->container = $container;
        $this->event = $event;
        $this->file = $file;
    }

    /**
     * Detect paths.
     *
     * @param string $path
     * @param string $namespace
     *
     * @return array
     */
    public function detect(string $path, string $namespace)
    {
        $classes = collect();

        return $classes->toArray();
    }

    /**
     * Do.
     *
     * @param $target
     */
    public function do($target)
    {
        $this->event->listen($target['event'], $target['listener']);
    }

    /**
     * Paths definition.
     *
     * @return array
     */
    public function paths()
    {
        $paths = collect();
        collect($this->file->directories($this->container->frameworkPath('src')))->each(function ($directory) use ($paths) {
            $location = $directory . DIRECTORY_SEPARATOR . 'Listeners';
            $this->file->isDirectory($location) && $paths->push([
                'namespace' => '\\Notadd\\Foundation\\' . $this->file->dirname($location) . '\\Listeners',
                'path'      => $location,
            ]);
        });

        return $paths->toArray();
    }
}