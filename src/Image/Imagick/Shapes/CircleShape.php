<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:44
 */
namespace Notadd\Foundation\Image\Imagick\Shapes;

use Notadd\Foundation\Image\Image;

/**
 * Class CircleShape.
 */
class CircleShape extends EllipseShape
{
    /**
     * @var int
     */
    public $diameter = 100;

    /**
     * @param int $diameter
     */
    public function __construct($diameter = null)
    {
        $this->width = is_numeric($diameter) ? intval($diameter) : $this->diameter;
        $this->height = is_numeric($diameter) ? intval($diameter) : $this->diameter;
        $this->diameter = is_numeric($diameter) ? intval($diameter) : $this->diameter;
    }

    /**
     * @param \Notadd\Foundation\Image\Image $image
     * @param int                            $x
     * @param int                            $y
     *
     * @return bool
     */
    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        return parent::applyToImage($image, $x, $y);
    }
}
