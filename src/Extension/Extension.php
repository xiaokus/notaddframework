<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-09-19 10:53
 */
namespace Notadd\Foundation\Extension;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Notadd\Foundation\Http\Traits\HasAttributes;

/**
 * Class Expand.
 */
class Extension implements Arrayable, ArrayAccess, JsonSerializable
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
     * @return string
     */
    public function service(): string
    {
        return $this->attributes['service'];
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->offsetExists('identification')
            && $this->offsetExists('description')
            && $this->offsetExists('authors');
    }
}
