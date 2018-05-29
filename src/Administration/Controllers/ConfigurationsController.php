<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-26 19:47
 */
namespace Notadd\Foundation\Administration\Controllers;

use Illuminate\Support\Str;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class ConfigurationsController.
 */
class ConfigurationsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function definition($path)
    {
        $path = Str::replaceFirst('-', '/', $path);
        $page = $this->administration->pages()->filter(function ($definition) use ($path) {
            return $definition['initialization']['path'] == $path;
        })->first();

        return $this->response->json([
            'data'    => $page,
            'message' => '获取数据成功！',
        ]);
    }
}
