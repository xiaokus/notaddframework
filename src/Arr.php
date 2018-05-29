<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-25 14:23
 */
namespace Notadd\Foundation;

use Illuminate\Support\Arr as IlluminateArr;

/**
 * Class Arr.
 */
class Arr extends IlluminateArr
{
    /**
     * @param callable $callback
     * @param array    $array
     */
    public static function each(callable $callback, array $array)
    {
        foreach ($array as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
    }

    /**
     * @param callable $callback
     * @param array    $array
     *
     * @return array
     */
    public static function map(callable $callback, array $array)
    {
        $keys = array_keys($array);
        $items = array_map($callback, $array, $keys);

        return array_combine($keys, $items);
    }
}
