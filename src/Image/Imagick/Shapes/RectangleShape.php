<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:46
 */
namespace Notadd\Foundation\Image\Imagick\Shapes;

use ImagickDraw;
use Notadd\Foundation\Image\AbstractShape;
use Notadd\Foundation\Image\Image;
use Notadd\Foundation\Image\Imagick\Color;

/**
 * Class RectangleShape.
 */
class RectangleShape extends AbstractShape
{
    /**
     * @var int
     */
    public $x1 = 0;

    /**
     * @var int
     */
    public $y1 = 0;

    /**
     * @var int
     */
    public $x2 = 0;

    /**
     * @var int
     */
    public $y2 = 0;

    /**
     * RectangleShape constructor.
     *
     * @param null $x1
     * @param null $y1
     * @param null $x2
     * @param null $y2
     */
    public function __construct($x1 = null, $y1 = null, $x2 = null, $y2 = null)
    {
        $this->x1 = is_numeric($x1) ? intval($x1) : $this->x1;
        $this->y1 = is_numeric($y1) ? intval($y1) : $this->y1;
        $this->x2 = is_numeric($x2) ? intval($x2) : $this->x2;
        $this->y2 = is_numeric($y2) ? intval($y2) : $this->y2;
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
        $rectangle = new ImagickDraw();
        $bgcolor = new Color($this->background);
        $rectangle->setFillColor($bgcolor->getPixel());
        if ($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            $rectangle->setStrokeWidth($this->border_width);
            $rectangle->setStrokeColor($border_color->getPixel());
        }
        $rectangle->rectangle($this->x1, $this->y1, $this->x2, $this->y2);
        $image->getCore()->drawImage($rectangle);

        return true;
    }
}
