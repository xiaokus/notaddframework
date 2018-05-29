<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-04-06 08:36
 */
namespace Notadd\Foundation\Yaml;

use Notadd\Foundation\Yaml\Exceptions\LoaderException;
use Notadd\Foundation\Yaml\Loaders\YamlLoader;
use Notadd\Foundation\Yaml\Validators\YamlValidator;

/**
 * Class YamlEnv.
 */
class YamlEnv
{
    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath;

    /**
     * The loader instance.
     *
     * @var \Notadd\Foundation\Yaml\Loaders\YamlLoader|null
     */
    protected $loader;

    /**
     * @var bool
     */
    private $castToUpper;

    /**
     * Create a new Yamlenv instance.
     *
     * @param string $path
     * @param string $file
     * @param bool   $castToUpper
     */
    public function __construct($path, $file = 'environment.yaml', $castToUpper = false)
    {
        $this->filePath = $this->getFilePath($path, $file);
        $this->castToUpper = $castToUpper;
    }

    /**
     * Load environment file in given directory.
     *
     * @return array
     */
    public function load()
    {
        return $this->loadData();
    }

    /**
     * Load environment file in given directory.
     *
     * @return array
     */
    public function overload()
    {
        return $this->loadData(true);
    }

    /**
     * Required ensures that the specified variables exist, and returns a new validator object.
     *
     * @param string|string[] $variable
     *
     * @return \Notadd\Foundation\Yaml\Validators\YamlValidator
     */
    public function required($variable)
    {
        $this->initialize();

        return new YamlValidator((array)$variable, $this->loader);
    }

    /**
     * Get loader instance
     *
     * @throws \Notadd\Foundation\Yaml\Exceptions\LoaderException
     *
     * @return \Notadd\Foundation\Yaml\Loaders\YamlLoader
     */
    public function getLoader()
    {
        if (!$this->loader) {
            throw new LoaderException('Loader has not been initialized yet.');
        }

        return $this->loader;
    }

    /**
     * Returns the full path to the file.
     *
     * @param string $path
     * @param string $file
     *
     * @return string
     */
    protected function getFilePath($path, $file)
    {
        if (!is_string($file)) {
            $file = 'env.yaml';
        }
        $filePath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;

        return $filePath;
    }

    /**
     * Initialize loader.
     *
     * @param bool $overload
     */
    protected function initialize($overload = false)
    {
        $this->loader = new YamlLoader($this->filePath, !$overload);
        if ($this->castToUpper) {
            $this->loader->forceUpperCase();
        }
    }

    /**
     * Actually load the data.
     *
     * @param bool $overload
     *
     * @return array
     */
    protected function loadData($overload = false)
    {
        $this->initialize($overload);

        return $this->loader->load();
    }
}
