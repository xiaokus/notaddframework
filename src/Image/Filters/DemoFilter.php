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
 * Class DemoFilter.
 */
class DemoFilter implements FilterInterface
{
    const DEFAULT_SIZE = 10;

    /**
     * @var int
     */
    private $size;

    /**
     * @param int $size
     */
    public function __construct($size = null)
    {
        $this->size = is_numeric($size) ? intval($size) : self::DEFAULT_SIZE;
    }

    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function applyFilter(Image $image)
    {
        $image->pixelate($this->size);
        $image->greyscale();

        return $image;
    }
}
