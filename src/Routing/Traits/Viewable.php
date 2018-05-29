<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-06-14 12:29
 */
namespace Notadd\Foundation\Routing\Traits;

use Illuminate\Support\Str;

/**
 * Trait Viewable.
 */
trait Viewable
{
    /**
     * Share variable with view.
     *
     * @param      $key
     * @param null $value
     */
    protected function share($key, $value = null)
    {
        $this->view->share($key, $value);
    }

    /**
     * Share variable with view.
     *
     * @param       $template
     * @param array $data
     * @param array $mergeData
     *
     * @return \Illuminate\Contracts\View\View
     */
    protected function view($template, array $data = [], $mergeData = [])
    {
        if (Str::contains($template, '::')) {
            return $this->view->make($template, $data, $mergeData);
        } else {
            return $this->view->make('theme::' . $template, $data, $mergeData);
        }
    }
}
