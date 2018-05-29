<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-09-14 20:27
 */
namespace Notadd\Foundation\Addon;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Notadd\Foundation\Http\Traits\HasAttributes;

/**
 * Class Extension.
 */
class Addon implements Arrayable, ArrayAccess, JsonSerializable
{
    use HasAttributes;

    /**
     * Extension constructor.
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
    public function identification(): string
    {
        return $this->attributes['identification'];
    }

    /**
     * Get The addon enabled.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function enabled(): bool
    {
        return (bool)$this->get('enabled', false);
    }

    /**
     * @return bool
     */
    public function installed(): bool
    {
        return boolval($this->attributes['installed'] ?? false);
    }

    /**
     * @return string
     */
    public function namespace(): string
    {
        return $this->attributes['namespace'];
    }

    /**
     * @return string
     */
    public function provider()
    {
        return $this->attributes['provider'];
    }

    /**
     * @return array
     */
    public function scripts(): array
    {
        $data = collect();
        collect(data_get($this->attributes, 'assets.scripts'))->each(function ($script) use ($data) {
            $data->put($this->attributes['identification'], asset($script));
        });

        return $data->toArray();
    }

    /**
     * @return array
     */
    public function stylesheets(): array
    {
        $data = collect();
        collect(data_get($this->attributes, 'assets.stylesheets'))->each(function ($stylesheet) use ($data) {
            $data->put($this->attributes['identification'], asset($stylesheet));
        });

        return $data->toArray();
    }

    /**
     * @return bool
     */
    public function validate()
    {
        return $this->offsetExists('name')
            && $this->offsetExists('identification')
//            && $this->offsetExists('description')
            && $this->offsetExists('author');
    }
}
