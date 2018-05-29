<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-22 15:23
 */
namespace Notadd\Foundation\Mail;

use Illuminate\Mail\MailServiceProvider as IlluminateMailServiceProvider;
use Illuminate\Mail\Markdown;

/**
 * Class MailServiceProvider.
 */
class MailServiceProvider extends IlluminateMailServiceProvider
{
    /**
     * Register the Markdown renderer instance.
     */
    protected function registerMarkdownRenderer()
    {
        $this->app->singleton(Markdown::class, function ($app) {
            $config = $app['config'];

            return new Markdown($app['view'], [
                'theme' => $config->get('mail.markdown.theme', 'default'),
                'paths' => $config->get('mail.markdown.paths', []),
            ]);
        });
    }
}
