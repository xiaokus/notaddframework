<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:24
 */
namespace Notadd\Foundation\Image\Commands;

/**
 * Class StreamCommand.
 */
class StreamCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $format = $this->argument(0)->value();
        $quality = $this->argument(1)->between(0, 100)->value();
        $this->setOutput(\GuzzleHttp\Psr7\stream_for($image->encode($format, $quality)->getEncoded()));

        return true;
    }
}
