<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:51
 */
namespace Notadd\Foundation\Image\Gd\Shapes;

use Notadd\Foundation\Image\AbstractShape;
use Notadd\Foundation\Image\Gd\Color;
use Notadd\Foundation\Image\Image;

/**
 * Class PolygonShape.
 */
class PolygonShape extends AbstractShape
{
    /**
     * @var int
     */
    public $points;

    /**
     * @param array $points
     */
    public function __construct($points)
    {
        $this->points = $points;
    }

    /**
     * @param Image $image
     * @param int   $x
     * @param int   $y
     *
     * @return bool
     */
    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        $background = new Color($this->background);
        imagefilledpolygon($image->getCore(), $this->points, intval(count($this->points) / 2), $background->getInt());
        if ($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            imagesetthickness($image->getCore(), $this->border_width);
            imagepolygon($image->getCore(), $this->points, intval(count($this->points) / 2), $border_color->getInt());
        }

        return true;
    }
}
