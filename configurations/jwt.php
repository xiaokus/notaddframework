<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-25 18:48
 */
return [
    'secret'                 => env('JWT_SECRET'),
    'keys'                   => [
        'passphrase' => env('JWT_PASSPHRASE'),
        'public'     => env('JWT_PUBLIC_KEY', 'storage/publicKey.pem'),
        'private'    => env('JWT_PRIVATE_KEY', 'storage/privateKey.pem'),
    ],
    'ttl'                    => env('JWT_TTL', 60),
    'refresh_ttl'            => env('JWT_REFRESH_TTL', 20160),
    'algo'                   => env('JWT_ALGO', 'HS384'),
    'required_claims'        => [
        'iss',
        'iat',
        'exp',
        'nbf',
        'sub',
        'jti',
    ],
    'persistent_claims'      => [
    ],
    'blacklist_enabled'      => env('JWT_BLACKLIST_ENABLED', true),
    'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),
    'providers'              => [
        'jwt'     => Notadd\Foundation\JWTAuth\Providers\JWT\Namshi::class,
        'auth'    => Notadd\Foundation\JWTAuth\Providers\Auth\Illuminate::class,
        'storage' => Notadd\Foundation\JWTAuth\Providers\Storage\Illuminate::class,
    ],
];