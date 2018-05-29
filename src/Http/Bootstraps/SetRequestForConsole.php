<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 11:07
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Illuminate\Http\Request;
use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class SetRequestForConsole.
 */
class SetRequestForConsole implements Bootstrap
{
    use Helpers;

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        $url = $this->config->get('app.url', 'http://localhost');
        $this->container->instance('request', Request::create($url, 'GET', [], [], [], $_SERVER));
    }
}
