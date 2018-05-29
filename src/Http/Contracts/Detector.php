<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-14 20:28
 */
namespace Notadd\Foundation\Http\Contracts;

/**
 * Interface Detector.
 */
interface Detector
{
    /**
     * Detect paths.
     *
     * @param string $path
     * @param string $namespace
     *
     * @return array
     */
    public function detect(string $path, string $namespace);

    /**
     * Do.
     *
     * @param $target
     */
    public function do($target);

    /**
     * Paths definition.
     *
     * @return array
     */
    public function paths();
}
