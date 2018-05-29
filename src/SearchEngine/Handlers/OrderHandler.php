<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-12 15:25
 */
namespace Notadd\Foundation\SearchEngine\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Module\ModuleManager;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Validation\Rule;

/**
 * Class OrderHandler.
 */
class OrderHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Module\ModuleManager
     */
    protected $module;

    /**
     * @var bool
     */
    protected $onlyValues = true;

    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * OrderHandler constructor.
     *
     * @param \Illuminate\Container\Container                         $container
     * @param \Notadd\Foundation\Module\ModuleManager                 $module
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $settings
     */
    public function __construct(Container $container, ModuleManager $module, SettingsRepository $settings)
    {
        parent::__construct($container);
        $this->settings = $settings;
        $this->module = $module;
    }

    /**
     * Execute Handler.
     *
     * @throws \Exception
     */
    protected function execute()
    {
        list($identification, $order) = $this->validate($this->request, [
            'identification' => Rule::required(),
            'order'          => [
                Rule::numeric(),
                Rule::required(),
            ],
        ], [
            'identification.required' => '模块表示必须填写',
            'order.numeric'           => '匹配排序必须为数值',
            'order.required'          => '匹配排序必须填写',
        ]);
        $this->settings->set('module.' . $identification . '.seo.order', $order);
        $this->withCode(200)->withMessage('设置模块匹配排序成功！');
    }
}
