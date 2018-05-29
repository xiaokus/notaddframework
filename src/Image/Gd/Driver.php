<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:31
 */
namespace Notadd\Foundation\Image\Gd;

use Notadd\Foundation\Image\AbstractColor;
use Notadd\Foundation\Image\AbstractDriver;
use Notadd\Foundation\Image\Exceptions\NotSupportedException;
use Notadd\Foundation\Image\Image;

/**
 * Class Driver.
 */
class Driver extends AbstractDriver
{
    /**
     * @param Decoder $decoder
     * @param Encoder $encoder
     */
    public function __construct(Decoder $decoder = null, Encoder $encoder = null)
    {
        if (!$this->coreAvailable()) {
            throw new NotSupportedException('GD Library extension not available with this PHP installation.');
        }
        $this->decoder = $decoder ? $decoder : new Decoder();
        $this->encoder = $encoder ? $encoder : new Encoder();
    }

    /**
     * Creates new image instance.
     *
     * @param int    $width
     * @param int    $height
     * @param string $background
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function newImage($width, $height, $background = null)
    {
        // create empty resource
        $core = imagecreatetruecolor($width, $height);
        $image = new Image(new static(), $core);
        // set background color
        $background = new Color($background);
        imagefill($image->getCore(), 0, 0, $background->getInt());

        return $image;
    }

    /**
     * @param string $value
     *
     * @return AbstractColor
     */
    public function parseColor($value)
    {
        return new Color($value);
    }

    /**
     * Checks if core module installation is available.
     *
     * @return bool
     */
    protected function coreAvailable()
    {
        return extension_loaded('gd') && function_exists('gd_info');
    }

    /**
     * Returns clone of given core.
     *
     * @return mixed
     */
    public function cloneCore($core)
    {
        $width = imagesx($core);
        $height = imagesy($core);
        $clone = imagecreatetruecolor($width, $height);
        imagealphablending($clone, false);
        imagesavealpha($clone, true);
        imagecopy($clone, $core, 0, 0, 0, 0, $width, $height);

        return $clone;
    }
}
