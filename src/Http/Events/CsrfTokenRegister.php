<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 10:40
 */
namespace Notadd\Foundation\Http\Events;

use Notadd\Foundation\Http\Middlewares\VerifyCsrfToken;

/**
 * Class CsrfTokenRegister.
 */
class CsrfTokenRegister
{
    /**
     * @var \Notadd\Foundation\Http\Middlewares\VerifyCsrfToken
     */
    protected $verifier;

    /**
     * CsrfTokenRegister constructor.
     *
     * @param \Notadd\Foundation\Http\Middlewares\VerifyCsrfToken $verifier
     *
     * @internal param \Illuminate\Container\Container $container
     */
    public function __construct(VerifyCsrfToken $verifier)
    {
        $this->verifier = $verifier;
    }

    /**
     * Register except to verifier.
     *
     * @param $excepts
     */
    public function registerExcept($excepts)
    {
        $this->verifier->registerExcept($excepts);
    }
}
