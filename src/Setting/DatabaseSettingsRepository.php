<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-24 10:09
 */
namespace Notadd\Foundation\Setting;

use Illuminate\Database\ConnectionInterface;
use Notadd\Foundation\Setting\Contracts\SettingsRepository as SettingsRepositoryContract;

/**
 * Class DatabaseSettingsRepository.
 */
class DatabaseSettingsRepository implements SettingsRepositoryContract
{
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $database;

    /**
     * DatabaseSettingsRepository constructor.
     *
     * @param \Illuminate\Database\ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->database = $connection;
    }

    /**
     * Get all settings.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->database->table('settings')->pluck('value', 'key');
    }

    /**
     * Delete a setting value.
     *
     * @param $keyLike
     */
    public function delete($keyLike)
    {
        $this->database->table('settings')->where('key', 'like', $keyLike)->delete();
    }

    /**
     * Get a setting value by key.
     *
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (is_null($value = $this->database->table('settings')->where('key', $key)->value('value'))) {
            return $default;
        }

        return $value;
    }

    /**
     * Set a setting value from key and value.
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $query = $this->database->table('settings')->where('key', $key);
        $method = $query->exists() ? 'update' : 'insert';
        $query->$method(compact('key', 'value'));
    }
}
