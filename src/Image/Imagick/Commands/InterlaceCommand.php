<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:59
 */
namespace Notadd\Foundation\Image\Imagick\Commands;

use Imagick;
use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class InterlaceCommand.
 */
class InterlaceCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $mode = $this->argument(0)->type('bool')->value(true);
        if ($mode) {
            $mode = Imagick::INTERLACE_LINE;
        } else {
            $mode = Imagick::INTERLACE_NO;
        }
        $image->getCore()->setInterlaceScheme($mode);

        return true;
    }
}
