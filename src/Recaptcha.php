<?php

namespace Davidvandertuijn;

use Davidvandertuijn\Recaptcha\Exceptions\EmptySecretException;

/**
 * reCAPTCHA.
 * @see https://developers.google.com/recaptcha/docs/verify
 */
class Recaptcha
{
    /**
     * @const string
     */
    const URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @var mixed
     */
    public $ch;

    /**
     * @var string
     */
    protected $remoteIp = '';

    /**
     * @var string
     */
    protected $response = '';

    /**
     * @var string
     */
    protected $secret = '';

    /**
     * Get Curl Handle.
     *
     * @return mixed
     */
    public function getCh()
    {
        return $this->ch;
    }

    /**
     * Set Curl Handle.
     *
     * @param mixed $ch
     */
    public function setCh($ch)
    {
        $this->ch = $ch;
    }

    /**
     * Get Remote Ip.
     *
     * @return string $this->response
     */
    public function getRemoteIp(): string
    {
        return $this->remoteIp;
    }

    /**
     * Set Remote Ip.
     *
     * @param string $remoteIp
     */
    public function setRemoteIp(string $remoteIp)
    {
        $this->remoteIp = $remoteIp;
    }

    /**
     * Get Response.
     *
     * @return string $this->response
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * Set Response.
     *
     * @param string $secret
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
    }

    /**
     * Get Secret.
     *
     * @return string $this->secret
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * Set Secret.
     *
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Before.
     *
     * @throws \Davidvandertuijn\Recaptcha\Exceptions\EmptySecretException
     */
    public function before()
    {
        // Check Secret.
        $this->checkSecret();
    }

    /**
     * Check Secret.
     *
     * @throws \Davidvandertuijn\Recaptcha\Exceptions\EmptySecretException
     */
    public function checkSecret()
    {
        if (empty($this->getSecret())) {
            throw new EmptySecretException();
        }
    }

    /**
     * cURL Setopt.
     */
    public function curlSetopt()
    {
        curl_setopt($this->getCh(), CURLOPT_URL, self::URL);
        curl_setopt($this->getCh(), CURLOPT_POST, true);
        curl_setopt($this->getCh(), CURLOPT_POSTFIELDS, $this->postfields());
        curl_setopt($this->getCh(), CURLINFO_HEADER_OUT, false);
        curl_setopt($this->getCh(), CURLOPT_HEADER, false);
        curl_setopt($this->getCh(), CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->getCh(), CURLOPT_SSL_VERIFYPEER, true);
    }

    /**
     * Postfields.
     *
     * @return array
     */
    public function postfields(): array
    {
        return [
            'secret' => $this->getSecret(),
            'response' => $this->getResponse(),
            'remoteip' => $this->getRemoteIp() == '::1' ? '127.0.0.1' : $this->getRemoteIp(),
        ];
    }

    /**
     * Success.
     *
     * @return bool
     */
    public function success(): bool
    {
        // Decodes a JSON string.
        $response = json_decode($this->getResponse());

        // Success.
        return (bool) $response->success;
    }

    /**
     * Verify.
     *
     * @return bool
     */
    public function verify(): bool
    {
        // Before
        $this->before();

        // Initialize a cURL session.
        $this->setCh(curl_init());

        // Sets multiple options for a cURL session.
        $this->curlSetopt();

        // Perform a cURL session.
        $this->setResponse(curl_exec($this->getCh()));

        // Success.
        return $this->success();
    }
}
