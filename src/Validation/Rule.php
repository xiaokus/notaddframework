<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-07-12 14:16
 */
namespace Notadd\Foundation\Validation;

use Illuminate\Validation\Rule as IlluminateRule;

/**
 * Class Rule.
 */
class Rule extends IlluminateRule
{
    /**
     * @return string
     */
    public static function array()
    {
        return 'array';
    }

    /**
     * @return string
     */
    public static function boolean()
    {
        return 'boolean';
    }

    /**
     * @param $format
     *
     * @return string
     */
    public static function dateFormat($format)
    {
        return 'date_format:' . $format;
    }

    /**
     * @return string
     */
    public static function email()
    {
        return 'email';
    }

    /**
     * @return string
     */
    public static function file()
    {
        return 'file';
    }

    /**
     * @return string
     */
    public static function image()
    {
        return 'image';
    }

    /**
     * @param array $mimeTypes
     *
     * @return string
     */
    public static function mimetypes(array $mimeTypes)
    {
        return 'mimetypes:' . implode(',', $mimeTypes);
    }

    /**
     * @return string
     */
    public static function numeric()
    {
        return 'numeric';
    }

    /**
     * @param $regex
     *
     * @return string
     */
    public static function regex($regex)
    {
        return 'regex:' . $regex;
    }

    /**
     * @return string
     */
    public static function required()
    {
        return 'required';
    }

    /**
     * @return string
     */
    public static function url()
    {
        return 'url';
    }
}
