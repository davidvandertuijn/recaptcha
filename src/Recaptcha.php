<?php

namespace Davidvandertuijn;

class Recaptcha
{
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    protected $sRemoteIp;
    protected $sResponse;
    protected $sSecret;

    /**
     * Get Remote Ip.
     *
     * @return string $this->sResponse
     */
    public function getRemoteIp()
    {
        return $this->sRemoteIp;
    }

    /**
     * Set Remote Ip.
     *
     * @param string $sRemoteIp
     */
    public function setRemoteIp($sRemoteIp)
    {
        $this->sRemoteIp = $sRemoteIp;
    }

    /**
     * Get Response.
     *
     * @return string $this->sResponse
     */
    public function getResponse()
    {
        return $this->sResponse;
    }

    /**
     * Set Response.
     *
     * @param string $sSecret
     */
    public function setResponse($sResponse)
    {
        $this->sResponse = $sResponse;
    }

    /**
     * Get Secret.
     *
     * @return string $this->sSecret
     */
    public function getSecret()
    {
        return $this->sSecret;
    }

    /**
     * Set Secret.
     *
     * @param string $sSecret
     */
    public function setSecret($sSecret)
    {
        $this->sSecret = $sSecret;
    }

    /**
     * Verify.
     *
     * @return string
     */
    public function verify()
    {
        $aPostfields = [
            'secret'   => $this->getSecret(),
            'response' => $this->getResponse(),
            'remoteip' => $this->getRemoteIp() == '::1' ? '127.0.0.1' : $this->getRemoteIp(),
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::SITE_VERIFY_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostfields);
        curl_setopt($ch, CURLINFO_HEADER_OUT, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $sResponse = curl_exec($ch);

        curl_close($ch);

        $oResponse = json_decode($sResponse);

        return $oResponse->success;
    }
}
