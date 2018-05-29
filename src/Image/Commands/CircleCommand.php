<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:13
 */
namespace Notadd\Foundation\Image\Commands;

use Closure;

/**
 * Class CircleCommand.
 */
class CircleCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $diameter = $this->argument(0)->type('numeric')->required()->value();
        $x = $this->argument(1)->type('numeric')->required()->value();
        $y = $this->argument(2)->type('numeric')->required()->value();
        $callback = $this->argument(3)->type('closure')->value();
        $circle_classname = sprintf('\Notadd\Foundation\Image\%s\Shapes\CircleShape',
            $image->getDriver()->getDriverName());
        $circle = new $circle_classname($diameter);
        if ($callback instanceof Closure) {
            $callback($circle);
        }
        $circle->applyToImage($image, $x, $y);

        return true;
    }
}
