<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:33
 */
namespace Notadd\Foundation\Image\Imagick;

use Notadd\Foundation\Image\AbstractColor;

/**
 * Class Color.
 */
class Color extends AbstractColor
{
    /**
     * @var \ImagickPixel
     */
    public $pixel;

    /**
     * @param int $value
     *
     * @return \Notadd\Foundation\Image\AbstractColor
     */
    public function initFromInteger($value)
    {
        $a = ($value >> 24) & 0xFF;
        $r = ($value >> 16) & 0xFF;
        $g = ($value >> 8) & 0xFF;
        $b = $value & 0xFF;
        $a = $this->rgb2alpha($a);
        $this->setPixel($r, $g, $b, $a);
    }

    /**
     * @param array $array
     *
     * @return \Notadd\Foundation\Image\AbstractColor
     */
    public function initFromArray($array)
    {
        $array = array_values($array);
        if (count($array) == 4) {
            list($r, $g, $b, $a) = $array;
        } elseif (count($array) == 3) {
            list($r, $g, $b) = $array;
            $a = 1;
        }
        $this->setPixel($r, $g, $b, $a);
    }

    /**
     * @param string $value
     *
     * @return \Notadd\Foundation\Image\AbstractColor
     */
    public function initFromString($value)
    {
        if ($color = $this->rgbaFromString($value)) {
            $this->setPixel($color[0], $color[1], $color[2], $color[3]);
        }
    }

    /**
     * @param \ImagickPixel $value
     *
     * @return \Notadd\Foundation\Image\AbstractColor
     */
    public function initFromObject($value)
    {
        if (is_a($value, '\ImagickPixel')) {
            $this->pixel = $value;
        }
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     *
     * @return \Notadd\Foundation\Image\AbstractColor
     */
    public function initFromRgb($r, $g, $b)
    {
        $this->setPixel($r, $g, $b);
    }

    /**
     * @param int   $r
     * @param int   $g
     * @param int   $b
     * @param float $a
     *
     * @return \Notadd\Foundation\Image\AbstractColor
     */
    public function initFromRgba($r, $g, $b, $a)
    {
        $this->setPixel($r, $g, $b, $a);
    }

    /**
     * @return int
     */
    public function getInt()
    {
        $r = $this->getRedValue();
        $g = $this->getGreenValue();
        $b = $this->getBlueValue();
        $a = intval(round($this->getAlphaValue() * 255));

        return intval(($a << 24) + ($r << 16) + ($g << 8) + $b);
    }

    /**
     * @param string $prefix
     *
     * @return string
     */
    public function getHex($prefix = '')
    {
        return sprintf('%s%02x%02x%02x', $prefix, $this->getRedValue(), $this->getGreenValue(), $this->getBlueValue());
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return [
            $this->getRedValue(),
            $this->getGreenValue(),
            $this->getBlueValue(),
            $this->getAlphaValue(),
        ];
    }

    /**
     * @return string
     */
    public function getRgba()
    {
        return sprintf('rgba(%d, %d, %d, %.2f)', $this->getRedValue(), $this->getGreenValue(), $this->getBlueValue(),
            $this->getAlphaValue());
    }

    /**
     * @param \Notadd\Foundation\Image\AbstractColor $color
     * @param int                                    $tolerance
     *
     * @return bool
     */
    public function differs(AbstractColor $color, $tolerance = 0)
    {
        $color_tolerance = round($tolerance * 2.55);
        $alpha_tolerance = round($tolerance);
        $delta = [
            'r' => abs($color->getRedValue() - $this->getRedValue()),
            'g' => abs($color->getGreenValue() - $this->getGreenValue()),
            'b' => abs($color->getBlueValue() - $this->getBlueValue()),
            'a' => abs($color->getAlphaValue() - $this->getAlphaValue()),
        ];

        return $delta['r'] > $color_tolerance or $delta['g'] > $color_tolerance or $delta['b'] > $color_tolerance or $delta['a'] > $alpha_tolerance;
    }

    /**
     * @return int
     */
    public function getRedValue()
    {
        return intval(round($this->pixel->getColorValue(\Imagick::COLOR_RED) * 255));
    }

    /**
     * @return int
     */
    public function getGreenValue()
    {
        return intval(round($this->pixel->getColorValue(\Imagick::COLOR_GREEN) * 255));
    }

    /**
     * @return int
     */
    public function getBlueValue()
    {
        return intval(round($this->pixel->getColorValue(\Imagick::COLOR_BLUE) * 255));
    }

    /**
     * @return float
     */
    public function getAlphaValue()
    {
        return round($this->pixel->getColorValue(\Imagick::COLOR_ALPHA), 2);
    }

    /**
     * @return \ImagickPixel
     */
    private function setPixel($r, $g, $b, $a = null)
    {
        $a = is_null($a) ? 1 : $a;

        return $this->pixel = new \ImagickPixel(sprintf('rgba(%d, %d, %d, %.2f)', $r, $g, $b, $a));
    }

    /**
     * @return \ImagickPixel
     */
    public function getPixel()
    {
        return $this->pixel;
    }

    /**
     * @param int $value
     *
     * @return float
     */
    private function rgb2alpha($value)
    {
        return (float)round($value / 255, 2);
    }
}
