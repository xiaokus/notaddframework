<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-23 16:25
 */
namespace Notadd\Foundation\Mail\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class SetHandler.
 */
class SetHandler extends Handler
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
    public function __construct(Container $container, SettingsRepository $settings) {
        parent::__construct($container);
        $this->settings = $settings;
    }
    /**
     * Execute Handler.
     */
    public function execute()
    {
        $this->settings->set('mail.driver', $this->request->input('driver'));
        $this->settings->set('mail.encryption', $this->request->input('encryption'));
        $this->settings->set('mail.port', $this->request->input('port'));
        $this->settings->set('mail.host', $this->request->input('host'));
        $this->settings->set('mail.from', $this->request->input('from'));
        $this->settings->set('mail.username', $this->request->input('username'));
        $this->settings->set('mail.password', $this->request->input('password'));
        $this->withCode(200)->withMessage('修改设置成功！');
    }
}
