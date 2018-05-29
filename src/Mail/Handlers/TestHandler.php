<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-15 17:01
 */
namespace Notadd\Foundation\Mail\Handlers;

use Illuminate\Container\Container;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class TestHandler.
 */
class TestHandler extends Handler
{
    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * TestHandler constructor.
     *
     * @param \Illuminate\Container\Container                         $container
     * @param \Illuminate\Contracts\Mail\Mailer                       $mailer
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $settings
     */
    public function __construct(Container $container, Mailer $mailer, SettingsRepository $settings)
    {
        parent::__construct($container);
        $this->mailer = $mailer;
        $this->settings = $settings;
    }

    /**
     * Execute Handler.
     */
    public function execute()
    {
        $this->container->make('log')->info('Mail testing', [$this->mailer]);
        $this->mailer->raw($this->request->input('content'), function (Message $message) {
            $message->to($this->request->input('to'));
            $message->subject('邮件功能测试');
        });
        $this->container->make('log')->info('Mail testing', [$this->mailer->failures()]);
        $this->withCode(200)->withMessage('测试邮件成功！');
    }
}
