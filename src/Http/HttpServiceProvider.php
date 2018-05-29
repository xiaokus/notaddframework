<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime      2016-10-21 10:52
 */
namespace Notadd\Foundation\Http;

use Illuminate\Contracts\Http\Kernel as KernelContract;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Routing\Redirector;
use Notadd\Foundation\Http\Abstracts\ServiceProvider;
use Notadd\Foundation\Http\Detectors\CommandDetector;
use Notadd\Foundation\Http\Detectors\SubscriberDetector;
//use Notadd\Foundation\Http\Middlewares\AssetsPublish;
use Notadd\Foundation\Http\Middlewares\CrossPreflight;
use Notadd\Foundation\Module\Module;
use Notadd\Foundation\Module\ModuleManager;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Symfony\Component\HttpFoundation\Request;
use Notadd\Foundation\JWTAuth\JWTGuard;

/**
 * Class HttpServiceProvider.
 */
class HttpServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $detectors = [
//        ListenerDetector::class,
        CommandDetector::class,
        SubscriberDetector::class,
    ];

    /**
     * Boot service provider.
     */
    public function boot()
    {
        $this->app->afterResolving(ValidatesWhenResolved::class, function (ValidatesWhenResolved $resolved) {
            $resolved->validate();
        });
        $this->app->make('request')->getMethod() == 'OPTIONS' && $this->app->make(KernelContract::class)->prependMiddleware(CrossPreflight::class);
//        $this->app->make(KernelContract::class)->prependMiddleware(AssetsPublish::class);
        $this->app->resolving(FormRequest::class, function (FormRequest $request, $app) {
            $this->initializeRequest($request, $app['request']);
            $request->setContainer($app)->setRedirector($this->app->make(Redirector::class));
        });
        $this->loadViewsFrom(realpath(__DIR__ . '/../../resources/errors'), 'error');
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../../databases/migrations'));
        if ($this->app->isInstalled()) {
            $this->app->make('router')->get('/', function (ModuleManager $module, SettingsRepository $setting) {
                if ($default = $setting->get('module.default')) {
                    $module = $module->get($default);
                    if ($module instanceof Module && $module->offsetExists('entry')) {
                        return $this->app->make('redirect')->to($module->get('entry'));
                    } else {
                        echo '模块入口未定义！';
                    }
                } else {
                    echo 'Notadd 已经安装成功！';
                }
            })->middleware('web');
        }
    }

    /**
     * Initialize the form request with data from the given request.
     *
     * @param \Notadd\Foundation\Http\FormRequest       $form
     * @param \Symfony\Component\HttpFoundation\Request $current
     */
    protected function initializeRequest(FormRequest $form, Request $current)
    {
        $files = $current->files->all();
        $files = is_array($files) ? array_filter($files) : $files;
        $form->initialize($current->query->all(), $current->request->all(), $current->attributes->all(),
            $current->cookies->all(), $files, $current->server->all(), $current->getContent());
        if ($session = $current->getSession()) {
            $form->setSession($session);
        }
        $form->setUserResolver($current->getUserResolver());
        $form->setRouteResolver($current->getRouteResolver());
    }

    /**
     * Register Service Provider.
     */
    public function register()
    {
        $this->app['auth']->extend('jwt', function ($app, $name, array $config) {
            $guard = new JwtGuard(
                $app['jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }
}
