<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 15:19
 */
namespace Notadd\Foundation\Image;

use Notadd\Foundation\Image\Exceptions\NotWritableException;
use Notadd\Foundation\Image\Exceptions\RuntimeException;
use Notadd\Foundation\Image\Filters\FilterInterface;

/**
 * Class Image.
 */
class Image extends File
{
    /**
     * Instance of current image driver
     *
     * @var AbstractDriver
     */
    protected $driver;

    /**
     * Image resource/object of current image processor
     *
     * @var mixed
     */
    protected $core;

    /**
     * Array of Image resource backups of current image processor
     *
     * @var array
     */
    protected $backups = [];

    /**
     * Last image encoding result
     *
     * @var string
     */
    public $encoded = '';

    /**
     * Image constructor.
     *
     * @param \Notadd\Foundation\Image\AbstractDriver|null $driver
     * @param mixed                                         $core
     */
    public function __construct(AbstractDriver $driver = null, $core = null)
    {
        $this->driver = $driver;
        $this->core = $core;
    }

    /**
     * Magic method to catch all image calls usually any AbstractCommand
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed|\Notadd\Foundation\Image\Image
     */
    public function __call($name, $arguments)
    {
        $command = $this->driver->executeCommand($this, $name, $arguments);

        return $command->hasOutput() ? $command->getOutput() : $this;
    }

    /**
     * Starts encoding of current image
     *
     * @param string $format
     * @param int    $quality
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function encode($format = null, $quality = 90)
    {
        return $this->driver->encode($this, $format, $quality);
    }

    /**
     * Saves encoded image in filesystem
     *
     * @param string $path
     * @param int    $quality
     *
     * @return \Notadd\Foundation\Image\Image
     * @throws \Notadd\Foundation\Image\Exceptions\NotWritableException
     */
    public function save($path = null, $quality = null)
    {
        $path = is_null($path) ? $this->basePath() : $path;
        if (is_null($path)) {
            throw new NotWritableException("Can't write to undefined path.");
        }
        $data = $this->encode(pathinfo($path, PATHINFO_EXTENSION), $quality);
        $saved = @file_put_contents($path, $data);
        if ($saved === false) {
            throw new NotWritableException("Can't write image data to path ({$path})");
        }
        $this->setFileInfoFromPath($path);

        return $this;
    }

    /**
     * Runs a given filter on current image
     *
     * @param \Notadd\Foundation\Image\Filters\FilterInterface $filter
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function filter(FilterInterface $filter)
    {
        return $filter->applyFilter($this);
    }

    /**
     * Returns current image driver
     *
     * @return \Notadd\Foundation\Image\AbstractDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Sets current image driver
     *
     * @param \Notadd\Foundation\Image\AbstractDriver $driver
     *
     * @return $this
     */
    public function setDriver(AbstractDriver $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Returns current image resource/obj
     *
     * @return mixed
     */
    public function getCore()
    {
        return $this->core;
    }

    /**
     * Sets current image resource
     *
     * @param $core
     *
     * @return $this
     */
    public function setCore($core)
    {
        $this->core = $core;

        return $this;
    }

    /**
     * Returns current image backup
     *
     * @param null $name
     *
     * @return mixed
     * @throws RuntimeException
     */
    public function getBackup($name = null)
    {
        $name = is_null($name) ? 'default' : $name;
        if (!$this->backupExists($name)) {
            throw new RuntimeException("Backup with name ({$name}) not available. Call backup() before reset().");
        }

        return $this->backups[$name];
    }

    /**
     * Returns all backups attached to image
     *
     * @return array
     */
    public function getBackups()
    {
        return $this->backups;
    }

    /**
     * Sets current image backup
     *
     * @param mixed  $resource
     * @param string $name
     *
     * @return self
     */
    public function setBackup($resource, $name = null)
    {
        $name = is_null($name) ? 'default' : $name;
        $this->backups[$name] = $resource;

        return $this;
    }

    /**
     * Checks if named backup exists
     *
     * @param string $name
     *
     * @return bool
     */
    private function backupExists($name)
    {
        return array_key_exists($name, $this->backups);
    }

    /**
     * Checks if current image is already encoded
     *
     * @return bool
     */
    public function isEncoded()
    {
        return !empty($this->encoded);
    }

    /**
     * Returns encoded image data of current image
     *
     * @return string
     */
    public function getEncoded()
    {
        return $this->encoded;
    }

    /**
     * Sets encoded image buffer
     *
     * @param string $value
     *
     * @return $this
     */
    public function setEncoded($value)
    {
        $this->encoded = $value;

        return $this;
    }

    /**
     * Calculates current image width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->getSize()->width;
    }

    /**
     * Alias of getWidth()
     *
     * @return int
     */
    public function width()
    {
        return $this->getWidth();
    }

    /**
     * Calculates current image height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->getSize()->height;
    }

    /**
     * Alias of getHeight
     *
     * @return int
     */
    public function height()
    {
        return $this->getHeight();
    }

    /**
     * Reads mime type
     *
     * @return string
     */
    public function mime()
    {
        return $this->mime;
    }

    /**
     * Returns encoded image data in string conversion
     *
     * @return string
     */
    public function __toString()
    {
        return $this->encoded;
    }

    /**
     * Cloning an image
     */
    public function __clone()
    {
        $this->core = $this->driver->cloneCore($this->core);
    }
}
