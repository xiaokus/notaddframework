<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:17
 */
namespace Notadd\Foundation\Image\Gd\Commands;

/**
 * Class HeightenCommand.
 */
class HeightenCommand extends ResizeCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $height = $this->argument(0)->type('digit')->required()->value();
        $additionalConstraints = $this->argument(1)->type('closure')->value();
        $this->arguments[0] = null;
        $this->arguments[1] = $height;
        $this->arguments[2] = function ($constraint) use ($additionalConstraints) {
            $constraint->aspectRatio();
            if (is_callable($additionalConstraints)) {
                $additionalConstraints($constraint);
            }
        };

        return parent::execute($image);
    }
}
