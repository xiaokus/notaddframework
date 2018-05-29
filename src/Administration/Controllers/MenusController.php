<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-25 16:51
 */
namespace Notadd\Foundation\Administration\Controllers;

use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class MenuController.
 */
class MenusController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return $this->response->json([
            'data'      => $this->module->menus()->structures()->toArray(),
            'message'   => '获取菜单数据成功！',
            'originals' => $this->module->menus()->toArray(),
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $data = $this->request->input('data');
        foreach ($data as $key=>$value) {
            unset($data[$key]['icon']);
            unset($data[$key]['parent']);
            unset($data[$key]['expand']);
            unset($data[$key]['path']);
            unset($data[$key]['permission']);
        }
        $this->setting->set('administration.menus', json_encode($data));
        $this->cache->tags('notadd')->flush();

        return $this->response->json([
            'message' => '批量更新数据成功！',
        ]);
    }
}
