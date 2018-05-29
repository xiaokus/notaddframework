<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-03 18:15
 */
namespace Notadd\Foundation\Permission;

use Notadd\Foundation\Module\Module;
use Notadd\Foundation\Permission\Repositories\PermissionRepository;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class PermissionManager.
 */
class PermissionManager
{
    use Helpers;

    /**
     * @var \Notadd\Foundation\Permission\Repositories\PermissionRepository
     */
    protected $repository;

    /**
     * @param $identification
     * @param $group
     *
     * @return bool
     */
    public function check($identification, $group)
    {
        return true;
    }

    /**
     * @return \Notadd\Foundation\Permission\Repositories\PermissionRepository
     */
    public function repository(): PermissionRepository
    {
        if (!$this->repository instanceof PermissionRepository) {
            $this->repository = new PermissionRepository();
            $collection = collect();
            $this->module->enabled()->each(function (Module $module) use ($collection) {
                if ($module->offsetExists('permissions')) {
                    $collection->put($module->identification(), $module->get('permissions'));
                }
            });
            $this->repository->initialize($collection);
        }

        return $this->repository;
    }
}
