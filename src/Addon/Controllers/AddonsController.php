<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-02-22 17:50
 */
namespace Notadd\Foundation\Addon\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Notadd\Foundation\Addon\Addon;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Foundation\Validation\Rule;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class ExtensionController.
 */
class AddonsController extends Controller
{
    /**
     * @var bool
     */
    protected $onlyValues = true;

    /**
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function enable(): JsonResponse
    {
        list($identification, $status) = $this->validate($this->request, [
            'identification' => Rule::required(),
            'status'         => Rule::required(),
        ], [
            'identification.required' => '插件标识必须填写',
            'status.required'         => '插件状态值必须填写',
        ]);
        if (!$this->addon->has($identification)) {
            return $this->response->json([
                'message' => '不存在插件[' . $identification . ']',
            ])->setStatusCode(500);
        }
        $key = 'addon.' . $identification . '.enabled';
        $this->setting->set($key, boolval($status));
        $this->cache->tags('notadd')->flush();

        return $this->response->json([
            'message' => '切换插件开启状态成功！',
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function install(): JsonResponse
    {
        list($identification) = $this->validate($this->request, [
            'identification' => Rule::required(),
        ], [
            'identification.required' => '插件标识必须填写',
        ]);
        set_time_limit(0);
        if (!$this->addon->has($identification)) {
            return $this->response->json([
                'message' => '插件不存在！',
            ])->setStatusCode(500);
        }
        $key = 'addon.' . $identification . '.installed';
        if ($this->setting->get($key, false)) {
            return $this->response->json([
                'message' => '插件已经安装，不必重复安装！',
            ])->setStatusCode(500);
        }
        $addon = $this->addon->get($identification);
        $output = new BufferedOutput();
        $this->db->transaction(function () use ($addon, $output) {
            if ($addon->offsetExists('migrations')) {
                $migrations = (array) $addon->get('migrations');
                collect($migrations)->each(function ($path) use ($addon, $output) {
                    $path = $addon->get('directory') . DIRECTORY_SEPARATOR . $path;
                    $migration = str_replace($this->container->basePath(), '', $path);
                    $migration = trim($migration, DIRECTORY_SEPARATOR);
                    $input = new ArrayInput([
                        '--path'  => $migration,
                        '--force' => true,
                    ]);
                    $this->getConsole()->find('migrate')->run($input, $output);
                });
            }
            if ($addon->offsetExists('publishes')) {
                $publishes = (array)$addon->get('publishes');
                collect($publishes)->each(function ($from, $to) use ($addon, $output) {
                    $from = $addon->get('directory') . DIRECTORY_SEPARATOR . $from;
                    $to = $this->container->basePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $to;
                    if ($this->file->isFile($from)) {
                        $this->publishFile($from, $to);
                    } else {
                        if ($this->file->isDirectory($from)) {
                            $this->publishDirectory($from, $to);
                        }
                    }
                });
            }
        });
        $notice = 'Install Addon ' . $this->request->input('identification') . ':';
        $this->container->make('log')->info($notice, explode(PHP_EOL, $output->fetch()));
        $this->setting->set('addon.' . $addon->identification() . '.installed', true);
        $this->cache->tags('notadd')->flush();

        return $this->response->json([
            'message' => '安装模块成功！',
        ])->setStatusCode(200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(): JsonResponse
    {
        $addons = $this->addon->repository();
        $enabled = $this->addon->enabled();
        $installed = $this->addon->installed();
        $notInstalled = $this->addon->notInstalled();

        return $this->response->json([
            'data'     => [
                'enabled'    => $this->info($enabled),
                'addons'     => $this->info($addons),
                'installed'  => $this->info($installed),
                'notInstall' => $this->info($notInstalled),
            ],
            'messages' => '获取插件列表成功！',
        ]);
    }

    /**
     * Info list.
     *
     * @param \Illuminate\Support\Collection $list
     *
     * @return array
     */
    protected function info(Collection $list)
    {
        $data = collect();
        $list->each(function (Addon $addon) use ($data) {
            $data->put($addon->identification(), [
                'author'         => collect($addon->offsetGet('author'))->implode(','),
                'enabled'        => $addon->enabled(),
                'description'    => $addon->offsetGet('description'),
                'identification' => $addon->identification(),
                'name'           => $addon->offsetGet('name'),
            ]);
        });

        return $data->toArray();
    }

    /**
     * @param string $identification
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uninstall(string $identification): JsonResponse
    {
        $identification = Str::replaceFirst('-', '/', $identification);
        if (!$this->addon->has($identification)) {
            return $this->response->json([
                'message' => '插件[' . $identification . ']不存在！',
            ])->setStatusCode(500);
        }
        if ($this->addon->enabled()->has($identification)) {
            return $this->response->json([
                'message' => '插件[' . $identification . ']已开启！',
            ])->setStatusCode(500);
        }
        set_time_limit(0);
        $addon = $this->addon->get($identification);
        $output = new BufferedOutput();
        $this->db->transaction(function () use ($addon, $output) {
            if ($addon->offsetExists('migrations')) {
                $migrations = (array)$addon->get('migrations');
                collect($migrations)->each(function ($path) use ($addon, $output) {
                    $path = $addon->get('directory') . DIRECTORY_SEPARATOR . $path;
                    $migration = str_replace($this->container->basePath(), '', $path);
                    $migration = trim($migration, DIRECTORY_SEPARATOR);
                    $input = new ArrayInput([
                        '--path'  => $migration,
                        '--force' => true,
                    ]);
                    $this->getConsole()->find('migrate:rollback')->run($input, $output);
                });
            }
        });
        $notice = 'Uninstall Addon ' . $identification . ':';
        $this->container->make('log')->info($notice, explode(PHP_EOL, $output->fetch()));
        $this->setting->set('addon.' . $identification . '.installed', false);
        $this->cache->tags('notadd')->flush();

        return $this->response->json([
            'message' => '卸载插件成功！',
        ]);
    }
}
