<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:55
 */
namespace Notadd\Foundation\Image\Imagick\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class FlipCommand.
 */
class FlipCommand extends AbstractCommand
{
    /**
     * Mirrors an image.
     *
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $mode = $this->argument(0)->value('h');
        if (in_array(strtolower($mode), [
            2,
            'v',
            'vert',
            'vertical',
        ])) {
            return $image->getCore()->flipImage();
        } else {
            return $image->getCore()->flopImage();
        }
    }
}
