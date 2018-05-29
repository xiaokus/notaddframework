<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-09-24 17:27
 */
namespace Notadd\Foundation\Member;

use Notadd\Foundation\Http\Abstracts\ServiceProvider;

/**
 * Class MemberServiceProvider.
 */
class MemberServiceProvider extends ServiceProvider
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
        return [
            'member',
            'member.manager',
        ];
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        $this->app->singleton('member', function () {
            return new MemberManagement();
        });
        $this->app->singleton('member.manager', function () {
            $manager = $this->app->make('member');

            return $manager->manager();
        });
    }
}
