<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 15:13
 */
namespace Notadd\Foundation\Image;

/**
 * Class AbstractFont.
 */
abstract class AbstractFont
{
    /**
     * Text to be written
     *
     * @var string
     */
    public $text;

    /**
     * Text size in pixels
     *
     * @var int
     */
    public $size = 12;

    /**
     * Color of the text
     *
     * @var mixed
     */
    public $color = '000000';

    /**
     * Rotation angle of the text
     *
     * @var int
     */
    public $angle = 0;

    /**
     * Horizontal alignment of the text
     *
     * @var string
     */
    public $align;

    /**
     * Vertical alignment of the text
     *
     * @var string
     */
    public $valign;

    /**
     * Path to TTF or GD library internal font file of the text
     *
     * @var mixed
     */
    public $file;

    /**
     * Draws font to given image on given position
     *
     * @param Image $image
     * @param int   $posx
     * @param int   $posy
     *
     * @return bool
     */
    abstract public function applyToImage(Image $image, $posx = 0, $posy = 0);

    /**
     * AbstractFont constructor.
     *
     * @param null $text
     */
    public function __construct($text = null)
    {
        $this->text = $text;
    }

    /**
     * Set text to be written
     *
     * @param string $text
     */
    public function text($text)
    {
        $this->text = $text;
    }

    /**
     * Get text to be written
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set font size in pixels
     *
     * @param int $size
     */
    public function size($size)
    {
        $this->size = $size;
    }

    /**
     * Get font size in pixels
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set color of text to be written
     *
     * @param mixed $color
     */
    public function color($color)
    {
        $this->color = $color;
    }

    /**
     * Get color of text
     *
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set rotation angle of text
     *
     * @param int $angle
     */
    public function angle($angle)
    {
        $this->angle = $angle;
    }

    /**
     * Get rotation angle of text
     *
     * @return int
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * Set horizontal text alignment
     *
     * @param string $align
     */
    public function align($align)
    {
        $this->align = $align;
    }

    /**
     * Get horizontal text alignment
     *
     * @return string
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * Set vertical text alignment
     *
     * @param string $valign
     */
    public function valign($valign)
    {
        $this->valign = $valign;
    }

    /**
     * Get vertical text alignment
     *
     * @return string
     */
    public function getValign()
    {
        return $this->valign;
    }

    /**
     * Set path to font file
     *
     * @param string $file
     */
    public function file($file)
    {
        $this->file = $file;
    }

    /**
     * Get path to font file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Checks if current font has access to an applicable font file
     *
     * @return bool
     */
    protected function hasApplicableFontFile()
    {
        if (is_string($this->file)) {
            return file_exists($this->file);
        }

        return false;
    }

    /**
     * Counts lines of text to be written
     *
     * @return int
     */
    public function countLines()
    {
        return count(explode(PHP_EOL, $this->text));
    }
}
