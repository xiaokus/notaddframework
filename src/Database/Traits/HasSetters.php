<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-07-18 17:43
 */
namespace Notadd\Foundation\Database\Traits;

use Closure;

/**
 * Trait HasSetters.
 */
trait HasSetters
{
    /**
     * @var array
     */
    protected $setters = [];

    /**
     * Set a given attribute on the model.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        if (isset($this->setters[$key])) {
            if (is_string($attributes = $this->setters[$key])) {
                list($rule, $default) = explode('|', $attributes);
                $format = null;
            } else {
                $rule = $attributes[0];
                $default = $attributes[1];
                $format = isset($attributes[3]) ?: null;
            }
            if ($rule instanceof Closure && $rule($value)) {
                parent::setAttribute($key, $default instanceof Closure ? $default($value) : $default);
            } else if (is_string($rule)) {
                switch ($rule) {
                    case 'empty':
                        if (empty($value)) {
                            parent::setAttribute($key, $default instanceof Closure ? $default($value) : $default);
                        } else {
                            parent::setAttribute($key, $format instanceof Closure ? $format($value) : $value);
                        }
                        break;
                    case 'null':
                        if (is_null($value)) {
                            parent::setAttribute($key, $default instanceof Closure ? $default($value) : $default);
                        } else {
                            parent::setAttribute($key, $format instanceof Closure ? $format($value) : $value);
                        }
                        break;
                    default:
                        parent::setAttribute($key, $format instanceof Closure ? $format($value) : $value);
                        break;
                }
            } else {
                parent::setAttribute($key, $format instanceof Closure ? $format($value) : $value);
            }
        } else {
            parent::setAttribute($key, $value);
        }

        return $this;
    }
}
