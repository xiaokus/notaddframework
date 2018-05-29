<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:23
 */
namespace Notadd\Foundation\Image\Commands;

use Notadd\Foundation\Image\Response;

/**
 * Class ResponseCommand.
 */
class ResponseCommand extends AbstractCommand
{
    /**
     * Builds HTTP response from given image.
     *
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function execute($image)
    {
        $format = $this->argument(0)->value();
        $quality = $this->argument(1)->between(0, 100)->value();
        $response = new Response($image, $format, $quality);
        $this->setOutput($response->make());

        return true;
    }
}
