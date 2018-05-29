<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 17:07
 */
namespace Notadd\Foundation\GraphQL;

use Notadd\Foundation\Http\Abstracts\ServiceProvider;

/**
 * Class GraphQLServiceProvider.
 */
class GraphQLServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @return array
     */
    public function provides()
    {
        return ['graphql'];
    }

    /**
     * Register Service Provider.
     */
    public function register()
    {
        $this->app->singleton('graphql', function ($app) {
            $manager = new GraphQLManager();
            foreach ($app['config']['graphql']['types'] as $type) {
                $manager->addType($type);
            }
            foreach ($app['config']['graphql']['schemas'] as $name => $definition) {
                $manager->addSchema($name, $definition);
            }

            return $manager;
        });
    }
}
