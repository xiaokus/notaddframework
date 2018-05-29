<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-16 16:11
 */
namespace Notadd\Foundation\JWTAuth\Providers\JWT;

use Exception;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Namshi\JOSE\Signer\OpenSSL\PublicKey;
use Notadd\Foundation\JWTAuth\Contracts\Providers\JWT;
use Notadd\Foundation\JWTAuth\Exceptions\TokenInvalidException;
use Notadd\Foundation\JWTAuth\JWS;
use ReflectionClass;
use ReflectionException;
use Notadd\Foundation\JWTAuth\Exceptions\JWTException;

/**
 * Class Namshi.
 */
class Namshi extends Provider implements JWT
{
    /**
     * The JWS.
     *
     * @var \Namshi\JOSE\JWS
     */
    protected $jws;

    /**
     * Namshi constructor.
     *
     * @param string $secret
     * @param string $algo
     * @param array  $keys
     * @param null   $driver
     */
    public function __construct($secret, $algo, array $keys = [], $driver = null)
    {
        parent::__construct($secret, $keys, $algo);
        $this->jws = $driver ?: new JWS(['typ' => 'JWT', 'alg' => $algo]);
    }

    /**
     * Create a JSON Web Token.
     *
     * @param  array $payload
     *
     * @throws \Notadd\Foundation\JWTAuth\Exceptions\JWTException
     *
     * @return string
     */
    public function encode(array $payload)
    {
        try {
            $this->jws->setPayload($payload)->sign($this->getSigningKey(), $this->getPassphrase());

            return (string)$this->jws->getTokenString();
        } catch (Exception $e) {
            throw new JWTException('Could not create token: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Decode a JSON Web Token.
     *
     * @param  string $token
     *
     * @throws \Notadd\Foundation\JWTAuth\Exceptions\JWTException
     *
     * @return array
     */
    public function decode($token)
    {
        try {
            // Let's never allow insecure tokens
            $jws = $this->jws->load($token, false);
        } catch (InvalidArgumentException $e) {
            throw new TokenInvalidException('Could not decode token: ' . $e->getMessage(), $e->getCode(), $e);
        }
        if (!$jws->verify($this->getVerificationKey(), $this->getAlgo())) {
            throw new TokenInvalidException('Token Signature could not be verified.');
        }

        return (array)$jws->getPayload();
    }

    /**
     * @return bool
     * @throws \Notadd\Foundation\JWTAuth\Exceptions\JWTException
     */
    protected function isAsymmetric()
    {
        try {
            if (in_array($this->getAlgo(), [
                'ES256',
                'ES384',
                'ES512',
            ])) {
                $class = 'Notadd\\Foundation\\JWTAuth\Signers\\OpenSSL\\%s';
            } else {
                $class = 'Namshi\\JOSE\\Signer\\OpenSSL\\%s';
            }

            return (new ReflectionClass(sprintf($class, $this->getAlgo())))->isSubclassOf(PublicKey::class);
        } catch (ReflectionException $e) {
            throw new JWTException('The given algorithm could not be found', $e->getCode(), $e);
        }
    }

    /**
     * Get the private key used to sign tokens
     * with an asymmetric algorithm.
     *
     * @return resource|string
     */
    public function getPrivateKey()
    {
        return file_get_contents(Arr::get($this->keys, 'private'));
    }

    /**
     * Get the public key used to sign tokens
     * with an asymmetric algorithm.
     *
     * @return resource|string
     */
    public function getPublicKey()
    {
        return file_get_contents(Arr::get($this->keys, 'public'));
    }

    /**
     * Get the key used to sign the tokens.
     *
     * @return resource|string
     */
    protected function getSigningKey()
    {
        return $this->isAsymmetric() ? $this->getPrivateKey() : $this->getSecret();
    }

    /**
     * Get the key used to verify the tokens.
     *
     * @return resource|string
     */
    protected function getVerificationKey()
    {
        return $this->isAsymmetric() ? $this->getPublicKey() : $this->getSecret();
    }
}
