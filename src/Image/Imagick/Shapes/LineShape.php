<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:44
 */
namespace Notadd\Foundation\Image\Imagick\Shapes;

use Notadd\Foundation\Image\AbstractShape;
use Notadd\Foundation\Image\Image;
use Notadd\Foundation\Image\Imagick\Color;

/**
 * Class LineShape.
 */
class LineShape extends AbstractShape
{
    /**
     * @var int
     */
    public $x = 0;

    /**
     * @var int
     */
    public $y = 0;

    /**
     * @var string
     */
    public $color = '#000000';

    /**
     * @var int
     */
    public $width = 1;

    /**
     * LineShape constructor.
     *
     * @param null $x
     * @param null $y
     */
    public function __construct($x = null, $y = null)
    {
        $this->x = is_numeric($x) ? intval($x) : $this->x;
        $this->y = is_numeric($y) ? intval($y) : $this->y;
    }

    /**
     * @param $color
     */
    public function color($color)
    {
        $this->color = $color;
    }

    /**
     * @param $width
     */
    public function width($width)
    {
        $this->width = $width;
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
        $line = new \ImagickDraw();
        $color = new Color($this->color);
        $line->setStrokeColor($color->getPixel());
        $line->setStrokeWidth($this->width);
        $line->line($this->x, $this->y, $x, $y);
        $image->getCore()->drawImage($line);

        return true;
    }
}
