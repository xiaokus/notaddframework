<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-23 15:09
 */
namespace Notadd\Foundation\Setting\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Validation\Rule;

/**
 * Class SetHandler.
 */
class HandlerHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * SetHandler constructor.
     *
     * @param \Illuminate\Container\Container                         $container
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $settings
     */
    public function __construct(Container $container, SettingsRepository $settings)
    {
        parent::__construct($container);
        $this->settings = $settings;
    }

    /**
     * Execute Handler.
     */
    public function execute()
    {
        $this->validate($this->request, [
            'name.value' => Rule::required(),
        ], [
            'name.value.required' => '网站名称必须填写',
        ]);
        $this->settings->set('site.beian', $this->request->input('beian.value', ''));
        $this->settings->set('site.company', $this->request->input('company.value', ''));
        $this->settings->set('site.copyright', $this->request->input('copyright.value', ''));
        $this->settings->set('site.domain', $this->request->input('domain.value', ''));
        $this->settings->set('site.enabled', $this->request->input('enabled.value', false));
        $this->settings->set('site.multidomain', $this->request->input('multidomain.value', false));
        $this->settings->set('site.name', $this->request->input('name.value', ''));
        $this->settings->set('site.statistics', $this->request->input('statistics.value', ''));
        $this->withCode(200)->withMessage('修改设置成功！');
    }
}
