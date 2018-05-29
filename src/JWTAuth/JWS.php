<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-16 16:17
 */
namespace Notadd\Foundation\JWTAuth;

use InvalidArgumentException;
use Namshi\JOSE\Base64\Base64Encoder;
use Namshi\JOSE\Base64\Base64UrlSafeEncoder;
use Namshi\JOSE\Base64\Encoder;
use Namshi\JOSE\JWT as NamshiJWT;

/**
 * Class JWS.
 */
class JWS extends NamshiJWT
{
    /**
     * @var string
     */
    protected $signature;

    /**
     * @var bool
     */
    protected $isSigned = false;

    protected $originalToken;

    protected $encodedSignature;

    protected $encryptionEngine;

    /**
     * @var array
     */
    protected $supportedEncryptionEngines = ['OpenSSL', 'SecLib'];

    /**
     * JWS constructor.
     *
     * @param array  $header
     * @param string $encryptionEngine
     */
    public function __construct($header = [], $encryptionEngine = 'OpenSSL')
    {
        $this->encryptionEngine = $encryptionEngine;
        parent::__construct([], $header);
    }

    /**
     * @param     $key
     * @param null $password
     *
     * @return string
     */
    public function sign($key, $password = null)
    {
        $this->signature = $this->getSigner()->sign($this->generateSigninInput(), $key, $password);
        $this->isSigned = true;

        return $this->signature;
    }

    /**
     * @return string|void
     */
    public function getSignature()
    {
        if ($this->isSigned()) {
            return $this->signature;
        }

        return;
    }

    /**
     * Checks whether the JSW has already been signed.
     *
     * @return bool
     */
    public function isSigned()
    {
        return (bool)$this->isSigned;
    }

    /**
     * Returns the string representing the JWT.
     *
     * @return string
     */
    public function getTokenString()
    {
        $signinInput = $this->generateSigninInput();

        return sprintf('%s.%s', $signinInput, $this->encoder->encode($this->getSignature()));
    }

    /**
     * Creates an instance of a JWS from a JWT.
     *
     * @param string  $jwsTokenString
     * @param bool    $allowUnsecure
     * @param Encoder $encoder
     * @param string  $encryptionEngine
     *
     * @return JWS
     *
     * @throws \InvalidArgumentException
     */
    public static function load(
        $jwsTokenString,
        $allowUnsecure = false,
        Encoder $encoder = null,
        $encryptionEngine = 'OpenSSL'
    ) {
        if ($encoder === null) {
            $encoder = strpbrk($jwsTokenString, '+/=') ? new Base64Encoder() : new Base64UrlSafeEncoder();
        }
        $parts = explode('.', $jwsTokenString);
        if (count($parts) === 3) {
            $header = json_decode($encoder->decode($parts[0]), true);
            $payload = json_decode($encoder->decode($parts[1]), true);
            if (is_array($header) && is_array($payload)) {
                if (strtolower($header['alg']) === 'none' && !$allowUnsecure) {
                    throw new InvalidArgumentException(sprintf('The token "%s" cannot be validated in a secure context, as it uses the unallowed "none" algorithm',
                        $jwsTokenString));
                }
                $jws = new static($header, $encryptionEngine);
                $jws->setEncoder($encoder)
                    ->setHeader($header)
                    ->setPayload($payload)
                    ->setOriginalToken($jwsTokenString)
                    ->setEncodedSignature($parts[2]);

                return $jws;
            }
        }
        throw new InvalidArgumentException(sprintf('The token "%s" is an invalid JWS', $jwsTokenString));
    }

    /**
     * @param resource|string $key
     * @param string          $algo The algorithms this JWS should be signed with. Use it if you want to restrict which algorithms you want to allow to be validated.
     *
     * @return bool
     */
    public function verify($key, $algo = null)
    {
        if (empty($key) || ($algo && $this->header['alg'] !== $algo)) {
            return false;
        }
        $decodedSignature = $this->encoder->decode($this->getEncodedSignature());
        $signinInput = $this->getSigninInput();

        return $this->getSigner()->verify($key, $decodedSignature, $signinInput);
    }

    /**
     * @return string
     */
    private function getSigninInput()
    {
        $parts = explode('.', $this->originalToken);
        if (count($parts) >= 2) {
            return sprintf('%s.%s', $parts[0], $parts[1]);
        }

        return $this->generateSigninInput();
    }

    /**
     * @param string $originalToken
     *
     * @return JWS
     */
    private function setOriginalToken($originalToken)
    {
        $this->originalToken = $originalToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getEncodedSignature()
    {
        return $this->encodedSignature;
    }

    /**
     * @param string $encodedSignature
     *
     * @return JWS
     */
    public function setEncodedSignature($encodedSignature)
    {
        $this->encodedSignature = $encodedSignature;

        return $this;
    }

    /**
     * @return \Namshi\JOSE\Signer\SignerInterface
     */
    protected function getSigner()
    {
        if ($this->encryptionEngine == 'OpenSSL' && in_array($this->header['alg'], [
                'ES256',
                'ES384',
                'ES512',
            ])) {
            $signerClass = sprintf('Notadd\Foundation\JWTAuth\Signers\\%s\\%s', $this->encryptionEngine,
                $this->header['alg']);
        } else {
            $signerClass = sprintf('Namshi\\JOSE\\Signer\\%s\\%s', $this->encryptionEngine, $this->header['alg']);
        }
        if (class_exists($signerClass)) {
            return new $signerClass();
        }
        throw new InvalidArgumentException(
            sprintf("The algorithm '%s' is not supported for %s", $this->header['alg'], $this->encryptionEngine));
    }
}
