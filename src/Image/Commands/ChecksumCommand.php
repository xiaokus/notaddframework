<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:12
 */
namespace Notadd\Foundation\Image\Commands;

/**
 * Class ChecksumCommand.
 */
class ChecksumCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $colors = [];
        $size = $image->getSize();
        for ($x = 0; $x <= ($size->width - 1); ++$x) {
            for ($y = 0; $y <= ($size->height - 1); ++$y) {
                $colors[] = $image->pickColor($x, $y, 'array');
            }
        }
        $this->setOutput(md5(serialize($colors)));

        return true;
    }
}
