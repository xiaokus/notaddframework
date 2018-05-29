<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 19:06
 */
namespace Notadd\Foundation\Image\Imagick\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class ResizeCanvasCommand.
 */
class ResizeCanvasCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $width = $this->argument(0)->type('digit')->required()->value();
        $height = $this->argument(1)->type('digit')->required()->value();
        $anchor = $this->argument(2)->value('center');
        $relative = $this->argument(3)->type('boolean')->value();
        $bgcolor = $this->argument(4)->value();
        $original_width = $image->getWidth();
        $original_height = $image->getHeight();
        $width = is_null($width) ? $original_width : intval($width);
        $height = is_null($height) ? $original_height : intval($height);
        if ($relative) {
            $width = $original_width + $width;
            $height = $original_height + $height;
        }
        $width = ($width <= 0) ? $width + $original_width : $width;
        $height = ($height <= 0) ? $height + $original_height : $height;
        $canvas = $image->getDriver()->newImage($width, $height, $bgcolor);
        $canvas_size = $canvas->getSize()->align($anchor);
        $image_size = $image->getSize()->align($anchor);
        $canvas_pos = $image_size->relativePosition($canvas_size);
        $image_pos = $canvas_size->relativePosition($image_size);
        if ($width <= $original_width) {
            $dst_x = 0;
            $src_x = $canvas_pos->x;
            $src_w = $canvas_size->width;
        } else {
            $dst_x = $image_pos->x;
            $src_x = 0;
            $src_w = $original_width;
        }
        if ($height <= $original_height) {
            $dst_y = 0;
            $src_y = $canvas_pos->y;
            $src_h = $canvas_size->height;
        } else {
            $dst_y = $image_pos->y;
            $src_y = 0;
            $src_h = $original_height;
        }
        $rect = new \ImagickDraw();
        $fill = $canvas->pickColor(0, 0, 'hex');
        $fill = $fill == '#ff0000' ? '#00ff00' : '#ff0000';
        $rect->setFillColor($fill);
        $rect->rectangle($dst_x, $dst_y, $dst_x + $src_w - 1, $dst_y + $src_h - 1);
        $canvas->getCore()->drawImage($rect);
        $canvas->getCore()->transparentPaintImage($fill, 0, 0, false);
        $canvas->getCore()->setImageColorspace($image->getCore()->getImageColorspace());
        $image->getCore()->cropImage($src_w, $src_h, $src_x, $src_y);
        $canvas->getCore()->compositeImage($image->getCore(), \Imagick::COMPOSITE_DEFAULT, $dst_x, $dst_y);
        $canvas->getCore()->setImagePage(0, 0, 0, 0);
        $image->setCore($canvas->getCore());

        return true;
    }
}
