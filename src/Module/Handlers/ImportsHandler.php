<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-08-20 18:13
 */
namespace Notadd\Foundation\Module\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Module\ModuleManager;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Validation\Rule;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ImportsHandler.
 */
class ImportsHandler extends Handler
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
     * ImportsHandler constructor.
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
        $this->validate($this->request, [
            'file' => [
                Rule::file(),
                Rule::required(),
            ],
        ], [
            'file.file'     => '文件必须成功上传',
            'file.required' => '文件必须上传',
        ]);
        $configurations = Yaml::parse(file_get_contents($this->request->file('file')->getRealPath()));
        $configurations = collect($configurations);
        if ($configurations->count()) {
            $configurations->each(function ($data, $identification) {
                $data = collect($data);
                $identification = trim($identification);
                if ($this->module->has($identification)) {
                    $data->has('settings') && collect($data->get('settings', []))->each(function ($value, $key) {
                        $this->setting->set($key, $value);
                    });
                }
            });
            $this->withCode(200)->withData($configurations)->withMessage('导入模块配置成功！');
        } else {
            $this->withCode(500)->withError('导入模块配置失败！');
        }
    }
}
