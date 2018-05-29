<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-09-22 16:18
 */
namespace Notadd\Foundation\Extension;

use Notadd\Foundation\Extension\Repositories\ExtensionRepository;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class ExtensionManager.
 */
class ExtensionManager
{
    use Helpers;

    /**
     * @var \Notadd\Foundation\Extension\Repositories\ExtensionRepository
     */
    protected $repository;

    /**
     * @param $identification
     *
     * @return bool
     */
    public function has($identification)
    {
        return $this->repository()->has($identification);
    }

    /**
     * @return \Notadd\Foundation\Extension\Repositories\ExtensionRepository
     */
    public function repository()
    {
        if (!$this->repository instanceof ExtensionRepository) {
            $this->repository = new ExtensionRepository();
            $this->repository->initialize(collect($this->file->directories($this->getExtensionPath())));
        }

        return $this->repository;
    }

    /**
     * @return string
     */
    protected function getExtensionPath(): string
    {
        return $this->container->extensionPath();
    }
}
