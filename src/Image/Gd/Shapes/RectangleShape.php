<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:52
 */
namespace Notadd\Foundation\Image\Gd\Shapes;

use Notadd\Foundation\Image\AbstractShape;
use Notadd\Foundation\Image\Gd\Color;
use Notadd\Foundation\Image\Image;

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
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
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
        $background = new Color($this->background);
        imagefilledrectangle($image->getCore(), $this->x1, $this->y1, $this->x2, $this->y2, $background->getInt());
        if ($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            imagesetthickness($image->getCore(), $this->border_width);
            imagerectangle($image->getCore(), $this->x1, $this->y1, $this->x2, $this->y2, $border_color->getInt());
        }

        return true;
    }
}
