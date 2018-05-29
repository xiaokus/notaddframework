<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:40
 */
namespace Notadd\Foundation\Image\Imagick;

use Notadd\Foundation\Image\AbstractFont;
use Notadd\Foundation\Image\Exceptions\RuntimeException;
use Notadd\Foundation\Image\Image;

/**
 * Class Font.
 */
class Font extends AbstractFont
{
    /**
     * @param Image $image
     * @param int   $posx
     * @param int   $posy
     *
     * @return bool|void
     */
    public function applyToImage(Image $image, $posx = 0, $posy = 0)
    {
        $draw = new \ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        if ($this->hasApplicableFontFile()) {
            $draw->setFont($this->file);
        } else {
            throw new RuntimeException('Font file must be provided to apply text to image.');
        }
        $color = new Color($this->color);
        $draw->setFontSize($this->size);
        $draw->setFillColor($color->getPixel());
        switch (strtolower($this->align)) {
            case 'center':
                $align = \Imagick::ALIGN_CENTER;
                break;
            case 'right':
                $align = \Imagick::ALIGN_RIGHT;
                break;
            default:
                $align = \Imagick::ALIGN_LEFT;
                break;
        }
        $draw->setTextAlignment($align);
        if (strtolower($this->valign) != 'bottom') {
            $dimensions = $image->getCore()->queryFontMetrics($draw, $this->text);
            switch (strtolower($this->valign)) {
                case 'center':
                case 'middle':
                    $posy = $posy + $dimensions['textHeight'] * 0.65 / 2;
                    break;
                case 'top':
                    $posy = $posy + $dimensions['textHeight'] * 0.65;
                    break;
            }
        }
        $image->getCore()->annotateImage($draw, $posx, $posy, $this->angle * (-1), $this->text);
    }
}
