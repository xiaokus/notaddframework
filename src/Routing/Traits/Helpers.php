<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-09-25 13:42
 */
namespace Notadd\Foundation\Routing\Traits;

use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Redis\RedisManager;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Illuminate\View\Factory;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\MountManager;
use Notadd\Foundation\Addon\AddonManager;
use Notadd\Foundation\Administration\AdministrationManager;
use Notadd\Foundation\Extension\ExtensionManager;
use Notadd\Foundation\Module\ModuleManager;
use Notadd\Foundation\Translation\Translator;
use Psr\Log\LoggerInterface;

/**
 * Class Helpers.
 *
 * @property \Notadd\Foundation\Addon\AddonManager                          $addon
 * @property \Notadd\Foundation\Administration\AdministrationManager        $administration
 * @property \Illuminate\Auth\AuthManager                                   $auth
 * @property \Illuminate\Cache\CacheManager|\Illuminate\Cache\TaggableStore $cache
 * @property \Illuminate\Config\Repository                                  $config
 * @property \Illuminate\Container\Container|\Notadd\Foundation\Application $container
 * @property \Illuminate\Database\Connection                                $db
 * @property \Illuminate\Events\Dispatcher                                  $event
 * @property \Notadd\Foundation\Extension\ExtensionManager                  $extension
 * @property \Illuminate\Filesystem\Filesystem                              $file
 * @property \Notadd\Foundation\GraphQL\GraphQLManager                      $graphql
 * @property \Notadd\Foundation\JWTAuth\JWTAuth                             $jwt
 * @property \Psr\Log\LoggerInterface                                       $log
 * @property \Notadd\Foundation\Module\ModuleManager                        $module
 * @property \Illuminate\Routing\Redirector                                 $redirector
 * @property \Illuminate\Redis\RedisManager                                 $redis
 * @property \Illuminate\Http\Request                                       $request
 * @property \Illuminate\Contracts\Routing\ResponseFactory                  $response
 * @property \Illuminate\Session\Store                                      $session
 * @property \Notadd\Foundation\Setting\Contracts\SettingsRepository        $setting
 * @property \Notadd\Foundation\Translation\Translator                      $translator
 * @property \Illuminate\Routing\UrlGenerator                               $url
 * @property \Illuminate\View\Factory                                       $view
 */
trait Helpers
{
    /**
     * @return mixed|\Notadd\Foundation\Addon\AddonManager
     */
    protected function getAddon(): AddonManager
    {
        return $this->container->make('addon');
    }

    /**
     * @return \Notadd\Foundation\Administration\AdministrationManager
     */
    protected function getAdministration(): AdministrationManager
    {
        return $this->container->make('administration');
    }

    /**
     * @return \Illuminate\Auth\AuthManager
     */
    protected function getAuth(): AuthManager
    {
        return $this->container->make('auth');
    }

    /**
     * Get configuration instance.
     *
     * @return \Illuminate\Contracts\Config\Repository
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getConfig()
    {
        return $this->container->make('config');
    }

    /**
     * @return \Illuminate\Database\DatabaseManager
     */
    protected function getDb(): DatabaseManager
    {
        return $this->container->make('db');
    }

    /**
     * Get console instance.
     *
     * @return \Illuminate\Contracts\Console\Kernel|\Notadd\Foundation\Console\Application
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getConsole()
    {
        $kernel = $this->container->make(Kernel::class);
        $kernel->bootstrap();

        return $kernel->getArtisan();
    }

    /**
     * Get IoC Container.
     *
     * @return \Illuminate\Container\Container
     */
    protected function getContainer(): Container
    {
        return Container::getInstance();
    }

    /**
     * @return \Notadd\Foundation\Extension\ExtensionManager
     */
    protected function getExtension(): ExtensionManager
    {
        return $this->container->make('extension');
    }

    /**
     * Get mailer instance.
     *
     * @return \Illuminate\Mail\Mailer
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getMailer(): Mailer
    {
        return $this->container->make('mailer');
    }

    /**
     * @return \Notadd\Foundation\Module\ModuleManager
     */
    protected function getModule(): ModuleManager
    {
        return $this->container->make('module');
    }

    /**
     * Get session instance.
     *
     * @return \Illuminate\Session\SessionManager
     */
    protected function getSession(): SessionManager
    {
        return $this->container->make('session');
    }

    /**
     * @return \Illuminate\Http\Request
     */
    protected function getRequest(): Request
    {
        return $this->container->make('request');
    }

    /**
     * @return \Illuminate\Routing\Redirector
     */
    protected function getRedirector(): Redirector
    {
        return $this->container->make('redirect');
    }

    /**
     * @return \Illuminate\Events\Dispatcher
     */
    protected function getEvent(): Dispatcher
    {
        return $this->container->make('events');
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        return $this->container->make('log');
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function getResponse()
    {
        return $this->container->make(ResponseFactory::class);
    }

    /**
     * Get setting instance.
     *
     * @return \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected function getSetting()
    {
        return $this->container->make('setting');
    }

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    protected function getFile()
    {
        return $this->container->make('files');
    }

    /**
     * @return \Illuminate\Routing\UrlGenerator
     */
    protected function getUrl()
    {
        return $this->container->make('url');
    }

    /**
     * @return \Notadd\Foundation\Translation\Translator
     */
    protected function getTranslator(): Translator
    {
        return $this->container->make('translator');
    }

    /**
     * @return \Illuminate\Cache\CacheManager
     */
    protected function getCache(): CacheManager
    {
        return $this->container->make('cache');
    }

    /**
     * @return \Illuminate\Redis\RedisManager
     */
    protected function getRedis(): RedisManager
    {
        return $this->container->make('redis');
    }

    /**
     * @return \Illuminate\View\Factory
     */
    protected function getView(): Factory
    {
        return $this->container->make('view');
    }

    /**
     * @return \Notadd\Foundation\GraphQL\GraphQLManager
     */
    protected function getGraphql()
    {
        return $this->container->make('graphql');
    }

    /**
     * @return \Notadd\Foundation\JWTAuth\JWTAuth
     */
    protected function getJwt()
    {
        return $this->container->make('jwt.auth');
    }

    /**
     * Publish the file to the given path.
     *
     * @param string $from
     * @param string $to
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function publishFile($from, $to)
    {
        $this->createParentDirectory(dirname($to));
        $this->file->copy($from, $to);
    }

    /**
     * Create the directory to house the published files if needed.
     *
     * @param $directory
     */
    protected function createParentDirectory($directory)
    {
        if (!$this->file->isDirectory($directory)) {
            $this->file->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Publish the directory to the given directory.
     *
     * @param $from
     * @param $to
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function publishDirectory($from, $to)
    {
        $manager = new MountManager([
            'from' => new Flysystem(new LocalAdapter($from)),
            'to'   => new Flysystem(new LocalAdapter($to)),
        ]);
        foreach ($manager->listContents('from://', true) as $file) {
            if ($file['type'] === 'file') {
                $manager->put('to://' . $file['path'], $manager->read('from://' . $file['path']));
            }
        }
    }

    /**
     * @param $key
     *
     * @return null
     */
    public function __get($key)
    {
        if (method_exists($this, 'get' . ucfirst($key))) {
            return $this->{'get' . ucfirst($key)}();
        }

        return null;
    }
}
