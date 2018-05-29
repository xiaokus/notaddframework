<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-12 16:30
 */
namespace Notadd\Foundation\Debug\Controllers;

use Illuminate\Http\JsonResponse;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class ConfigurationsController.
 */
class ConfigurationsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(): JsonResponse
    {
        return $this->response->json([
            'data'    => [
                'debug'   => boolval($this->setting->get('debug.enabled', false)),
                'testing' => boolval($this->setting->get('debug.testing', false)),
            ],
            'message' => '获取调试配置项成功！',
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(): JsonResponse
    {
        $this->setting->set('debug.enabled', boolval($this->request->input('enabled', false)));
        $this->setting->set('debug.testing', boolval($this->request->input('testing', false)));

        return $this->response->json([
            'message' => '修改调试配置成功！',
        ]);
    }
}
