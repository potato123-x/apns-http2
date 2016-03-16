<?php

namespace Nfilin\Libs\MobileNotifications\Authorization;

/**
 * Class Apns
 * @package Nfilin\Libs\MobileNotifications\Authorization
 */
class Apns implements AuthorizationInterface
{
    /**
     * @var string
     * Path to certificate key file
     */
    public $certificate;
    /**
     * @var string
     */
    public $passPhrase;

    /**
     * Apns constructor.
     * @param string $certificate
     * @param string $passPhrase
     */
    public function __construct($certificate, $passPhrase = '')
    {
        $this->certificate = $certificate;
        $this->passPhrase = $passPhrase;
    }

}