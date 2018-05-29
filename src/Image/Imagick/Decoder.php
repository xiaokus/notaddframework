<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:35
 */
namespace Notadd\Foundation\Image\Imagick;

use Imagick;
use ImagickException;
use Notadd\Foundation\Image\AbstractDecoder;
use Notadd\Foundation\Image\Exceptions\NotReadableException;
use Notadd\Foundation\Image\Exceptions\NotSupportedException;
use Notadd\Foundation\Image\Image;

/**
 * Class Decoder.
 */
class Decoder extends AbstractDecoder
{
    /**
     * @param string $path
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function initFromPath($path)
    {
        $core = new Imagick();
        try {
            $core->readImage($path);
            $core->setImageType(Imagick::IMGTYPE_TRUECOLOR);
        } catch (ImagickException $e) {
            throw new NotReadableException("Unable to read image from path ({$path}).", 0, $e);
        }
        $image = $this->initFromImagick($core);
        $image->setFileInfoFromPath($path);

        return $image;
    }

    /**
     * @param resource $resource
     *
     * @return \Notadd\Foundation\Image\Image|void
     */
    public function initFromGdResource($resource)
    {
        throw new NotSupportedException('Imagick driver is unable to init from GD resource.');
    }

    /**
     * @param Imagick $object
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function initFromImagick(Imagick $object)
    {
        $object = $this->removeAnimation($object);
        $object->setImageOrientation(Imagick::ORIENTATION_UNDEFINED);

        return new Image(new Driver(), $object);
    }

    /**
     * @param string $binary
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function initFromBinary($binary)
    {
        $core = new Imagick();
        try {
            $core->readImageBlob($binary);
        } catch (ImagickException $e) {
            throw new NotReadableException('Unable to read image from binary data.', 0, $e);
        }
        $image = $this->initFromImagick($core);
        $image->mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);

        return $image;
    }

    /**
     * @param Imagick $object
     *
     * @return Imagick
     */
    private function removeAnimation(Imagick $object)
    {
        $imagick = new Imagick();
        foreach ($object as $frame) {
            $imagick->addImage($frame->getImage());
            break;
        }
        $object->destroy();

        return $imagick;
    }
}
