<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:51
 */
namespace Notadd\Foundation\JWTAuth;

use Notadd\Foundation\JWTAuth\Http\Parser\Parser;
use Notadd\Foundation\JWTAuth\Contracts\Providers\Auth;

/**
 * Class JWTAuth.
 */
class JWTAuth extends JWT
{
    /**
     * The authentication provider.
     *
     * @var \Notadd\Foundation\JWTAuth\Contracts\Providers\Auth
     */
    protected $auth;

    /**
     * JWTAuth constructor.
     *
     * @param \Notadd\Foundation\JWTAuth\Manager                  $manager
     * @param \Notadd\Foundation\JWTAuth\Contracts\Providers\Auth $auth
     * @param \Notadd\Foundation\JWTAuth\Http\Parser\Parser       $parser
     */
    public function __construct(Manager $manager, Auth $auth, Parser $parser)
    {
        parent::__construct($manager, $parser);
        $this->auth = $auth;
    }

    /**
     * Attempt to authenticate the user and return the token.
     *
     * @param array  $credentials
     *
     * @return false|string
     */
    public function attempt(array $credentials)
    {
        if (! $this->auth->byCredentials($credentials)) {
            return false;
        }

        return $this->fromUser($this->user());
    }

    /**
     * Authenticate a user via a token.
     *
     * @return \Notadd\Foundation\JWTAuth\Contracts\JWTSubject|false
     */
    public function authenticate()
    {
        $id = $this->getPayload()->get('sub');

        if (! $this->auth->byId($id)) {
            return false;
        }

        return $this->user();
    }

    /**
     * Alias for authenticate().
     *
     * @return \Notadd\Foundation\JWTAuth\Contracts\JWTSubject|false
     */
    public function toUser()
    {
        return $this->authenticate();
    }

    /**
     * Get the authenticated user.
     *
     * @return \Notadd\Foundation\JWTAuth\Contracts\JWTSubject
     */
    public function user()
    {
        return $this->auth->user();
    }
}
