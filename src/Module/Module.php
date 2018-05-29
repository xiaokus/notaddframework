<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-12-13 21:06
 */
namespace Notadd\Foundation\Module;

use ArrayAccess;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use JsonSerializable;
use Notadd\Foundation\Database\Model;
use Notadd\Foundation\Http\Traits\HasAttributes;
use Notadd\Foundation\Member\Member;
use Notadd\Foundation\Permission\PermissionManager;

/**
 * Class Module.
 */
class Module implements Arrayable, ArrayAccess, JsonSerializable
{
    use HasAttributes;

    /**
     * Module constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function directory()
    {
        return $this->attributes['directory'];
    }

    /**
     * @return string
     */
    public function namespace()
    {
        return $this->attributes['namespace'];
    }

    /**
     * @return string
     */
    public function identification(): string
    {
        return $this->attributes['identification'];
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return boolval($this->attributes['enabled']);
    }

    /**
     * @return bool
     */
    public function isInstalled(): bool
    {
        return boolval($this->attributes['installed']);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function pages(): Collection
    {
        return collect($this->get('pages', []))->map(function ($definition, $identification) {
            $definition['initialization']['identification'] = $identification;
            unset($definition['initialization']['tabs']);

            return $definition['initialization'];
        })->groupBy('target');
    }

    /**
     * @return string
     */
    public function service(): string
    {
        return $this->attributes['service'];
    }

    /**
     * @param string $entry
     *
     * @return array
     */
    public function scripts($entry): array
    {
        $data = collect();
        $exists = collect(data_get($this->attributes, 'assets.' . $entry));
        $exists->isNotEmpty() && $exists->each(function ($definitions, $identification) use ($data) {
            if (isset($definitions['permissions']) && $definitions['permissions']) {
                if ($this->checkPermission($definitions['permissions'])) {
                    $scripts = $definitions['scripts'];
                } else {
                    $scripts = [];
                }
            } else {
                $scripts = $definitions['scripts'];
            }
            collect((array)$scripts)->each(function ($script) use ($data, $identification) {
                $data->put($identification, asset($script));
            });
        });

        return $data->toArray();
    }

    /**
     * @param $identification
     *
     * @return bool
     */
    protected function checkPermission($identification): bool
    {
        if (!$identification) {
            return true;
        }
        $user = Container::getInstance()->make(Factory::class)->guard('api')->user();
        if ((!$user instanceof Model) || (!Member::hasMacro('groups'))) {
            return false;
        }
        foreach (collect($user->load('groups')->getAttribute('groups'))->toArray() as $group) {
            if (Container::getInstance()->make(PermissionManager::class)->check($identification, $group['identification'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $entry
     *
     * @return array
     */
    public function stylesheets($entry): array
    {
        $data = collect();
        $exists = collect(data_get($this->attributes, 'assets.' . $entry));
        $exists->isNotEmpty() && $exists->each(function ($definitions, $identification) use ($data) {
            if (isset($definitions['permissions']) && $definitions['permissions']) {
                if ($this->checkPermission($definitions['permissions'])) {
                    $scripts = $definitions['stylesheets'];
                } else {
                    $scripts = [];
                }
            } else {
                $scripts = $definitions['stylesheets'];
            }
            collect((array)$scripts)->each(function ($script) use ($data, $identification) {
                $data->put($identification, asset($script));
            });
        });

        return $data->toArray();
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->offsetExists('name')
            && $this->offsetExists('identification')
            && $this->offsetExists('description')
            && $this->offsetExists('authors');
    }
}
