<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:37
 */
namespace Notadd\Foundation\Image\Imagick;

use Imagick;
use Notadd\Foundation\Image\AbstractDriver;
use Notadd\Foundation\Image\Exceptions\NotSupportedException;
use Notadd\Foundation\Image\Image;

/**
 * Class Driver.
 */
class Driver extends AbstractDriver
{
    /**
     * @param \Notadd\Foundation\Image\Imagick\Decoder|null $decoder
     * @param \Notadd\Foundation\Image\Imagick\Encoder|null $encoder
     */
    public function __construct(Decoder $decoder = null, Encoder $encoder = null)
    {
        if (!$this->coreAvailable()) {
            throw new NotSupportedException('ImageMagick module not available with this PHP installation.');
        }
        $this->decoder = $decoder ? $decoder : new Decoder();
        $this->encoder = $encoder ? $encoder : new Encoder();
    }

    /**
     * @param int    $width
     * @param int    $height
     * @param string $background
     *
     * @return Image
     */
    public function newImage($width, $height, $background = null)
    {
        $background = new Color($background);
        $core = new Imagick();
        $core->newImage($width, $height, $background->getPixel(), 'png');
        $core->setType(Imagick::IMGTYPE_UNDEFINED);
        $core->setImageType(Imagick::IMGTYPE_UNDEFINED);
        $core->setColorspace(Imagick::COLORSPACE_UNDEFINED);
        $image = new Image(new static(), $core);

        return $image;
    }

    /**
     * @param string $value
     *
     * @return \Notadd\Foundation\Image\AbstractColor
     */
    public function parseColor($value)
    {
        return new Color($value);
    }

    /**
     * @return bool
     */
    protected function coreAvailable()
    {
        return extension_loaded('imagick') && class_exists('Imagick');
    }
}
