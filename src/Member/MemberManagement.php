<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-13 16:12
 */
namespace Notadd\Foundation\Member;

use InvalidArgumentException;
use Notadd\Foundation\Member\Abstracts\Manager;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class MemberManagement.
 */
class MemberManagement
{
    use Helpers;

    /**
     * @var array
     */
    protected $drivers = [];

    /**
     * @var string
     */
    protected $default;

    /**
     * @var \Notadd\Foundation\Member\Abstracts\Manager
     */
    protected $manager;

    /**
     * Create a member.
     *
     * @param array $data
     * @param bool  $force
     *
     * @return \Notadd\Foundation\Member\Member
     */
    public function create(array $data, $force = false)
    {
        return $this->manager->create($data, $force);
    }

    /**
     * Delete a member.
     *
     * @param array $data
     * @param bool  $force
     *
     * @return \Notadd\Foundation\Member\Member
     */
    public function delete(array $data, $force = false)
    {
        return $this->manager->delete($data, $force);
    }

    /**
     * Edit a member info.
     *
     * @param array $data
     * @param bool  $force
     *
     * @return \Notadd\Foundation\Member\Member
     */
    public function edit(array $data, $force = false)
    {
        return $this->manager->edit($data, $force);
    }

    /**
     * Find a member.
     *
     * @param $key
     *
     * @return \Notadd\Foundation\Member\Member
     */
    public function find($key)
    {
        return $this->manager->find($key);
    }

    /**
     * Get manager instance.
     *
     * @return \Notadd\Foundation\Member\Abstracts\Manager
     */
    public function manager()
    {
        return $this->manager;
    }

    /**
     * Register member manager instance.
     *
     * @param \Notadd\Foundation\Member\Abstracts\Manager $manager
     */
    public function registerManager(Manager $manager)
    {
        if (is_object($this->manager)) {
            throw new InvalidArgumentException('Member Manager has been Registered!');
        }
        if ($manager instanceof Manager) {
            $this->manager = $manager;
            $this->manager->init();
        } else {
            throw new InvalidArgumentException('Member Manager must be instanceof ' . Manager::class . '!');
        }
    }

    /**
     * Store a member info.
     *
     * @param array $data
     * @param bool  $force
     *
     * @return \Notadd\Foundation\Member\Member
     */
    public function store(array $data, $force = false)
    {
        return $this->manager->store($data, $force);
    }

    /**
     * Update a member info.
     *
     * @param array $data
     * @param bool  $force
     *
     * @return \Notadd\Foundation\Member\Member
     */
    public function update(array $data, $force = false)
    {
        return $this->manager->update($data, $force);
    }
}
