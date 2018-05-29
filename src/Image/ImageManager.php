<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 15:24
 */
namespace Notadd\Foundation\Image;

use Closure;
use Illuminate\Container\Container;
use Notadd\Foundation\Image\Exceptions\MissingDependencyException;
use Notadd\Foundation\Image\Exceptions\NotSupportedException;

/**
 * Class ImageManager.
 */
class ImageManager
{
    /**
     * Config
     *
     * @var array
     */
    public $config = [
        'driver' => 'gd',
    ];

    /**
     * ImageManager constructor.
     *
     * @param array $config
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(array $config = [])
    {
        $this->checkRequirements();
        $this->configure($config);
    }

    /**
     * Overrides configuration settings
     *
     * @param array $config
     *
     * @return $this
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function configure(array $config = [])
    {
        $this->config = array_replace($this->config, $config);
        $this->config['driver'] = Container::getInstance()->make('setting')->get('attachment.engine', 'gd');

        return $this;
    }

    /**
     * Initiates an Image instance from different input types
     *
     * @param mixed $data
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function make($data)
    {
        return $this->createDriver()->init($data);
    }

    /**
     * Creates an empty image canvas
     *
     * @param int   $width
     * @param int   $height
     * @param mixed $background
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function canvas($width, $height, $background = null)
    {
        return $this->createDriver()->newImage($width, $height, $background);
    }

    /**
     * Create new cached image and run callback
     *
     * @param \Closure $callback
     * @param int      $lifetime
     * @param bool     $returnObj
     *
     * @return \Notadd\Foundation\Image\Image
     *
     * @throws MissingDependencyException
     */
    public function cache(Closure $callback, $lifetime = null, $returnObj = false)
    {
        if (class_exists('Notadd\\Image\\ImageCache')) {
            $imagecache = new ImageCache($this);
            if (is_callable($callback)) {
                $callback($imagecache);
            }

            return $imagecache->get($lifetime, $returnObj);
        }
        throw new MissingDependencyException('Please install package imagecache before running this function.');
    }

    /**
     * Creates a driver instance according to config settings
     *
     * @return \Notadd\Foundation\Image\AbstractDriver
     *
     * @throws NotSupportedException
     */
    private function createDriver()
    {
        $drivername = ucfirst($this->config['driver']);
        $driverclass = sprintf('Notadd\\Foundation\\Image\\%s\\Driver', $drivername);
        if (class_exists($driverclass)) {
            return new $driverclass();
        }
        throw new NotSupportedException("Driver ({$drivername}) could not be instantiated.");
    }

    /**
     * Check if all requirements are available
     *
     * @throws \Notadd\Foundation\Image\Exceptions\MissingDependencyException
     */
    private function checkRequirements()
    {
        if (!function_exists('finfo_buffer')) {
            throw new MissingDependencyException('PHP Fileinfo extension must be installed/enabled to use Notadd Image.');
        }
    }
}
