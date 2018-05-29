<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-08-29 14:07
 */
namespace Notadd\Foundation\Addon;

use Illuminate\Support\Collection;
use Notadd\Foundation\Addon\Repositories\AddonRepository;
use Notadd\Foundation\Addon\Repositories\AssetsRepository;
use Notadd\Foundation\Addon\Repositories\NavigationRepository;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class ExtensionManager.
 */
class AddonManager
{
    use Helpers;

    /**
     * @var \Notadd\Foundation\Addon\Repositories\AssetsRepository
     */
    protected $assetsRepository;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $excepts;

    /**
     * @var \Notadd\Foundation\Addon\Repositories\NavigationRepository
     */
    protected $navigationRepository;

    /**
     * @var \Notadd\Foundation\Addon\Repositories\AddonRepository
     */
    protected $repository;

    /**
     * AddonManager constructor.
     */
    public function __construct()
    {
        $this->excepts = collect();
    }

    /**
     * Get a extension by name.
     *
     * @param $name
     *
     * @return \Notadd\Foundation\Addon\Addon
     */
    public function get($name): Addon
    {
        return $this->repository()->get($name);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function enabled(): Collection
    {
        return $this->repository()->enabled();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function installed(): Collection
    {
        return $this->repository()->installed();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function notInstalled(): Collection
    {
        return $this->repository()->notInstalled();
    }

    /**
     * @return \Notadd\Foundation\Addon\Repositories\NavigationRepository
     */
    public function navigations()
    {
        if (!$this->navigationRepository instanceof NavigationRepository) {
            $collection = $this->enabled()->map(function (Addon $addon) {
                return $addon->offsetExists('navigations') ? (array)$addon->get('navigations') : [];
            });
            $this->navigationRepository = new NavigationRepository();
            $this->navigationRepository->initialize($collection);
        }
        return $this->navigationRepository;
    }

    /**
     * @return \Notadd\Foundation\Addon\Repositories\AddonRepository
     */
    public function repository(): AddonRepository
    {
        if (!$this->repository instanceof AddonRepository) {
            $collection = collect();
            if ($this->container->isInstalled()) {
                collect($this->file->directories($this->getExtensionPath()))->each(function ($vendor) use ($collection) {
                    collect($this->file->directories($vendor))->each(function ($directory) use ($collection) {
                        $collection->push($directory);
                    });
                });
            }
            $this->repository = new AddonRepository();
            $this->repository->initialize($collection);
        }

        return $this->repository;
    }

    /**
     * Path for extension.
     *
     * @return string
     */
    public function getExtensionPath(): string
    {
        return $this->container->addonPath();
    }

    /**
     * Check for extension exist.
     *
     * @param $name
     *
     * @return bool
     */
    public function has($name): bool
    {
        return $this->repository()->has($name);
    }

    /**
     * @return \Notadd\Foundation\Addon\Repositories\AssetsRepository
     */
    public function assets()
    {
        if (!$this->assetsRepository instanceof AssetsRepository) {
            $collection = collect();
            $this->repository->enabled()->each(function (Addon $addon) use ($collection) {
                $collection->put($addon->identification(), $addon->get('assets', []));
            });
            $this->assetsRepository = new AssetsRepository();
            $this->assetsRepository->initialize($collection);
        }

        return $this->assetsRepository;
    }

    /**
     * Vendor Path.
     *
     * @return string
     */
    public function getVendorPath(): string
    {
        return $this->container->basePath() . DIRECTORY_SEPARATOR . 'vendor';
    }

    /**
     * @return array
     */
    public function getExcepts()
    {
        return $this->excepts->toArray();
    }

    /**
     * @param $excepts
     */
    public function registerExcept($excepts)
    {
        foreach ((array)$excepts as $except) {
            $this->excepts->push($except);
        }
    }
}
