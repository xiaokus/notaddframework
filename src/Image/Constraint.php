<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 15:15
 */
namespace Notadd\Foundation\Image;

/**
 * Class Constraint.
 */
class Constraint
{
    /**
     * Bit value of aspect ratio constraint
     */
    const ASPECTRATIO = 1;

    /**
     * Bit value of upsize constraint
     */
    const UPSIZE = 2;

    /**
     * Constraint size
     *
     * @var \Notadd\Foundation\Image\Size
     */
    private $size;

    /**
     * Integer value of fixed parameters
     *
     * @var int
     */
    private $fixed = 0;

    /**
     * Constraint constructor.
     *
     * @param \Notadd\Foundation\Image\Size $size
     */
    public function __construct(Size $size)
    {
        $this->size = $size;
    }

    /**
     * Returns current size of constraint
     *
     * @return \Notadd\Foundation\Image\Size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Fix the given argument in current constraint
     *
     * @param int $type
     */
    public function fix($type)
    {
        $this->fixed = ($this->fixed & ~(1 << $type)) | (1 << $type);
    }

    /**
     * Checks if given argument is fixed in current constraint
     *
     * @param int $type
     *
     * @return bool
     */
    public function isFixed($type)
    {
        return (bool)($this->fixed & (1 << $type));
    }

    /**
     * Fixes aspect ratio in current constraint
     *
     * @return void
     */
    public function aspectRatio()
    {
        $this->fix(self::ASPECTRATIO);
    }

    /**
     * Fixes possibility to size up in current constraint
     *
     * @return void
     */
    public function upsize()
    {
        $this->fix(self::UPSIZE);
    }
}
