<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:51
 */
namespace Notadd\Foundation\JWTAuth\Http\Parser;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class LumenRouteParams extends RouteParams
{
    /**
     * Try to get the token from the route parameters.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return null|string
     */
    public function parse(Request $request)
    {
        // WARNING: Only use this parser if you know what you're doing!
        // It will only work with poorly-specified aspects of certain Lumen releases.
        // Route is the expected kind of array, and has a parameter with the key we want.
        return Arr::get($request->route(), '2.'.$this->key);
    }
}
