<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-14 16:17
 */
namespace Notadd\Foundation\JWTAuth;

use Illuminate\Contracts\Foundation\Application;
use Notadd\Foundation\Http\Abstracts\ServiceProvider;
use Notadd\Foundation\JWTAuth\Commands\JWTGenerateCommand;
use Notadd\Foundation\JWTAuth\Claims\Factory as ClaimFactory;
use Notadd\Foundation\JWTAuth\Http\Parser\AuthHeaders;
use Notadd\Foundation\JWTAuth\Http\Parser\Cookies;
use Notadd\Foundation\JWTAuth\Http\Parser\InputSource;
use Notadd\Foundation\JWTAuth\Http\Parser\Parser;
use Notadd\Foundation\JWTAuth\Http\Parser\QueryString;
use Notadd\Foundation\JWTAuth\Http\Parser\RouteParams;
use Notadd\Foundation\JWTAuth\Validators\PayloadValidator;

/**
 * Class JWTAuthServiceProvider.
 */
class JWTAuthServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'jwt',
            'jwt.auth',
            'jwt.blacklist',
            'jwt.claim.factory',
            'jwt.manager',
            'jwt.parser',
            'jwt.payload.factory',
            'jwt.provider.auth',
            'jwt.provider.jwt',
            'jwt.provider.storage',
            'jwt.secret',
            'jwt.validators.payload',
        ];
    }

    /**
     * Register Service Provider.
     */
    public function register()
    {
        $this->app->singleton('jwt', function ($app) {
            return new JWT(
                $app['jwt.manager'],
                $app['jwt.parser']
            );
        });
        $this->app->singleton('jwt.auth', function ($app) {
            return new JWTAuth(
                $app['jwt.manager'],
                $app['jwt.provider.auth'],
                $app['jwt.parser']
            );
        });
        $this->app->singleton('jwt.blacklist', function ($app) {
            $instance = new Blacklist($app['jwt.provider.storage']);
            $instance->setGracePeriod($app['config']['jwt.blacklist_grace_period']);
            $instance->setRefreshTTL($app['config']['jwt.refresh_ttl']);

            return $instance;
        });
        $this->app->singleton('jwt.claim.factory', function ($app) {
            $factory = new ClaimFactory($app['request']);
            $app->refresh('request', $factory, 'setRequest');
            $factory->setTTL($app['config']['jwt.ttl']);

            return $factory;
        });
        $this->app->singleton('jwt.manager', function ($app) {
            $instance = new Manager(
                $app['jwt.provider.jwt'],
                $app['jwt.blacklist'],
                $app['jwt.payload.factory']
            );
            $instance->setBlacklistEnabled((bool)$app['config']['jwt.blacklist_enabled']);
            $instance->setPersistentClaims((array)$app['config']['jwt.persistent_claims']);

            return $instance;
        });
        $this->app->singleton('jwt.parser', function (Application $app) {
            $parser = new Parser(
                $app['request'],
                [
                    new AuthHeaders,
                    new QueryString,
                    new InputSource,
                    new RouteParams,
                    new Cookies,
                ]
            );
            $app->refresh('request', $parser, 'setRequest');

            return $parser;
        });
        $this->app->singleton('jwt.payload.factory', function ($app) {
            return new Factory(
                $app['jwt.claim.factory'],
                $app['jwt.validators.payload']
            );
        });
        $this->app->singleton('jwt.provider.auth', function ($app) {
            return $this->getConfigInstance($app['config']['jwt.providers.auth']);
        });
        $this->app->singleton('jwt.provider.jwt', function ($app) {
            $keys = $app['config']['jwt.keys'];
            $keys['private'] = realpath($app['path.storage'] . '/privateKey.pem');
            $keys['public'] = realpath($app['path.storage'] . '/publicKey.pem');
            $provider = $app['config']['jwt.providers.jwt'];

            return new $provider(
                $app['config']['jwt.secret'],
                $app['config']['jwt.algo'],
                $keys
            );
        });
        $this->app->singleton('jwt.provider.storage', function ($app) {
            return $this->getConfigInstance($app['config']['jwt.providers.storage']);
        });
        $this->app->singleton('jwt.secret', function () {
            return new JWTGenerateCommand();
        });
        $this->app->singleton('jwt.validators.payload', function ($app) {
            $instance = new PayloadValidator();
            $instance->setRefreshTTL($app['config']['jwt.refresh_ttl']);
            $instance->setRequiredClaims($app['config']['jwt.required_claims']);

            return $instance;
        });
    }

    /**
     * Get an instantiable configuration instance. Pinched from dingo/api :).
     *
     * @param mixed $instance
     *
     * @return object
     */
    protected function getConfigInstance($instance)
    {
        if (is_callable($instance)) {
            return call_user_func($instance, $this->app);
        } else if (is_string($instance)) {
            return $this->app->make($instance);
        }

        return $instance;
    }
}
