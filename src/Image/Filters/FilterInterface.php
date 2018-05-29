<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:07
 */
namespace Notadd\Foundation\Image\Filters;

use Notadd\Foundation\Image\Image;

/**
 * Interface FilterInterface.
 */
interface FilterInterface
{
    /**\
     * @param  \Notadd\Foundation\Image\Image $image
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function applyFilter(Image $image);
}
