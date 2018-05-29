<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:05
 */
namespace Notadd\Foundation\Image\Gd\Commands;

/**
 * Class FlipCommand.
 */
class FlipCommand extends ResizeCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $mode = $this->argument(0)->value('h');
        $size = $image->getSize();
        $dst = clone $size;
        switch (strtolower($mode)) {
            case 2:
            case 'v':
            case 'vert':
            case 'vertical':
                $size->pivot->y = $size->height - 1;
                $size->height = $size->height * (-1);
                break;
            default:
                $size->pivot->x = $size->width - 1;
                $size->width = $size->width * (-1);
                break;
        }

        return $this->modify($image, 0, 0, $size->pivot->x, $size->pivot->y, $dst->width, $dst->height, $size->width,
            $size->height);
    }
}
