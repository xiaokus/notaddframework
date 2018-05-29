<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:41
 */
namespace Notadd\Foundation\Image\Imagick\Shapes;

use ImagickDraw;
use Notadd\Foundation\Image\AbstractShape;
use Notadd\Foundation\Image\Image;
use Notadd\Foundation\Image\Imagick\Color;

/**
 * Class EllipseShape.
 */
class EllipseShape extends AbstractShape
{
    /**
     * @var int
     */
    public $width = 100;

    /**
     * @var int
     */
    public $height = 100;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct($width = null, $height = null)
    {
        $this->width = is_numeric($width) ? intval($width) : $this->width;
        $this->height = is_numeric($height) ? intval($height) : $this->height;
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
        $circle = new ImagickDraw();
        $bgcolor = new Color($this->background);
        $circle->setFillColor($bgcolor->getPixel());
        if ($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            $circle->setStrokeWidth($this->border_width);
            $circle->setStrokeColor($border_color->getPixel());
        }
        $circle->ellipse($x, $y, $this->width / 2, $this->height / 2, 0, 360);
        $image->getCore()->drawImage($circle);

        return true;
    }
}
