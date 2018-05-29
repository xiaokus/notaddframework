<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime      2016-10-24 10:11
 */
namespace Notadd\Foundation\Setting;

use Notadd\Foundation\Setting\Contracts\SettingsRepository as SettingsRepositoryContract;

/**
 * Class MemoryCacheSettingsRepository.
 */
class MemoryCacheSettingsRepository implements SettingsRepositoryContract
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $inner;

    /**
     * @var bool
     */
    protected $isCached;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var array
     */
    protected $formats = [];

    /**
     * MemoryCacheSettingsRepository constructor.
     *
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $inner
     */
    public function __construct(SettingsRepositoryContract $inner)
    {
        $this->inner = $inner;
    }

    /**
     * Get all settings.
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function all()
    {
        if (!$this->isCached) {
            $this->cache = $this->inner->all();
            $this->isCached = true;
        }

        return $this->cache;
    }

    /**
     * Delete a setting value.
     *
     * @param $key
     */
    public function delete($key)
    {
        unset($this->cache[$key]);
        $this->inner->delete($key);
    }

    /**
     * Get a setting value by key.
     *
     * @param      $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        } else {
            if (is_null($default) && isset($this->formats[$key])) {
                $default = $this->formats[$key]['default'] ?? null;
            }
            $value = array_get($this->all(), $key, $default);
            if (isset($this->formats[$key]) && isset($this->formats[$key]['type'])) {
                switch ($this->formats[$key]['type']) {
                    case 'boolean':
                        $value = boolval($value);
                        break;
                    case 'integer':
                        $value = intval($value);
                        break;
                }
            }

            return $value;
        }
    }

    /**
     * @param string $key
     * @param $definition
     */
    public function registerFormat(string $key, $definition)
    {
        $this->formats[$key] = $definition;
    }

    /**
     * Set a setting value from key and value.
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->cache[$key] = $value;
        $this->inner->set($key, $value);
    }
}
