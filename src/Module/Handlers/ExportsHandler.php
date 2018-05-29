<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-08-15 17:03
 */
namespace Notadd\Foundation\Module\Handlers;

use Carbon\Carbon;
use Illuminate\Container\Container;
use Notadd\Foundation\Module\Module;
use Notadd\Foundation\Module\ModuleManager;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Validation\Rule;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ExportsHandler.
 */
class ExportsHandler extends Handler
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
     * ExportsHandler constructor.
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
            'modules' => [
                Rule::array(),
                Rule::required(),
            ],
        ], [
            'modules.array'    => '模块数据必须为数组',
            'modules.required' => '模块数据必须填写',
        ]);
        $output = collect();
        collect($this->request->input('modules'))->each(function ($identification) use ($output) {
            $module = $this->module->get($identification);
            $exports = collect();
            if ($module instanceof Module) {
                $exports->put('name', $module->offsetGet('name'));
                $exports->put('version', $module->offsetGet('version'));
                $exports->put('time', Carbon::now());
                $exports->put('secret', false);
                if ($module->offsetExists('exports')) {
                    $handler = $this->container->make($module->get('exports'));
                    $data = collect($handler->exports());
                    $data->count() && $exports->put('data', $data);
                }
                $settings = collect($module->get('settings', []));
                $settings->count() && $exports->put('settings', $settings->map(function ($default, $key) {
                    return $this->setting->get($key, $default);
                }));
                $output->put($identification, $exports);
            }
        });
        $output = Yaml::dump($output->toArray(), 8);
        $this->withCode(200)->withData([
            'content' => $output,
            'file'    => 'Notadd module export ' . Carbon::now()->format('Y-m-d H:i:s') . '.yaml',
        ])->withMessage('导出数据成功！');
    }
}
