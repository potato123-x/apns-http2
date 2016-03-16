<?php
namespace Nfilin\Libs\ApnsHttp2;

use Nfilin\Libs\MobileNotifications\Authorization\AuthorizationInterface;

/**
 * Class Certificate
 * @package Nfilin\Libs\ApnsHttp2
 */
class Certificate implements AuthorizationInterface
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