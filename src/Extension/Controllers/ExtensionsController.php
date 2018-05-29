<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-09-30 16:36
 */
namespace Notadd\Foundation\Extension\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Foundation\Validation\Rule;

/**
 * Class ExtensionsController.
 */
class ExtensionsController extends Controller
{
    /**
     * @var bool
     */
    protected $onlyValues = true;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function install(): JsonResponse
    {
        list($identification) = $this->validate($this->request, [
            'identification' => Rule::required(),
        ], [
            'identification.required' => '拓展标识必须填写',
        ]);
        if (!$this->extension->has($identification)) {
            return $this->response->json([
                'message' => '拓展不存在！',
            ])->setStatusCode(500);
        }
        $key = 'extension.' . $identification . '.require.install';
        $this->setting->set($key, true);
        $this->cache->tags('notadd')->flush();

        return $this->response->json([
            'message' => '添加到待安装列表成功!',
        ]);
    }

    /**
     * @param $identification
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uninstall($identification): JsonResponse
    {
        $identification = Str::replaceFirst('-', '/', $identification);
        if (!$this->extension->has($identification)) {
            return $this->response->json([
                'message' => '拓展不存在！',
            ])->setStatusCode(500);
        }
        $key = 'extension.' . $identification . '.require.uninstall';
        $this->setting->set($key, true);
        $this->cache->tags('notadd')->flush();

        return $this->response->json([
            'message' => '添加到待安装列表成功!',
        ]);
    }
}
