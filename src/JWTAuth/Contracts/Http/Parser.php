<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:46
 */
namespace Notadd\Foundation\JWTAuth\Contracts\Http;

use Illuminate\Http\Request;

/**
 * Interface Parser.
 */
interface Parser
{
    /**
     * Parse the request.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return null|string
     */
    public function parse(Request $request);
}
