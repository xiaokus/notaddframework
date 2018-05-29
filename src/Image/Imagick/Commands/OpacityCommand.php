<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 19:03
 */
namespace Notadd\Foundation\Image\Imagick\Commands;

use Imagick;
use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class OpacityCommand.
 */
class OpacityCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $transparency = $this->argument(0)->between(0, 100)->required()->value();
        $transparency = $transparency > 0 ? (100 / $transparency) : 1000;

        return $image->getCore()->evaluateImage(Imagick::EVALUATE_DIVIDE, $transparency, Imagick::CHANNEL_ALPHA);
    }
}
