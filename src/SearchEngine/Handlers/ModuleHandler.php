<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-12 12:48
 */
namespace Notadd\Foundation\SearchEngine\Handlers;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Notadd\Foundation\Module\Module;
use Notadd\Foundation\Module\ModuleManager;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class ModuleHandler.
 */
class ModuleHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Module\ModuleManager
     */
    protected $module;

    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $setting;

    /**
     * ModuleHandler constructor.
     *
     * @param \Illuminate\Container\Container                         $container
     * @param \Notadd\Foundation\Module\ModuleManager                 $module
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $setting
     */
    public function __construct(Container $container, ModuleManager $module, SettingsRepository $setting)
    {
        parent::__construct($container);
        $this->module = $module;
        $this->setting = $setting;
    }

    /**
     * Execute Handler.
     *
     * @throws \Exception
     */
    protected function execute()
    {
        $modules = collect();
        $this->module->enabled()->each(function (Module $module) use ($modules) {
            $modules->put($module->identification(), [
                'alias'          => $this->setting->get('module.' . $module->identification() . '.domain.alias', ''),
                'identification' => Str::replaceFirst('/', '-', $module->identification()),
                'name'           => $module->get('name'),
                'order'          => intval($this->setting->get('module.' . $module->identification() . '.seo.order', 0)),
            ]);
        });
        $modules->put('global', [
            'alias'          => '/',
            'identification' => 'global',
            'name'           => '全局',
            'order'          => 99999,
        ]);
        $modules->forget('notadd/administration');
        $this->withCode(200)->withData($modules->sortBy('order'))->withMessage('获取模块列表成功！');
    }
}
