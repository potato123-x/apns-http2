<?php

namespace Nfilin\Libs\MobileNotifications\Device;

/**
 * Class Apns
 * @package Nfilin\Libs\MobileNotifications\Device
 * @property string|null path
 */
class Apns extends Device
{
    public $type = self::TYPE_APNS;

    /**
     * Apns constructor.
     * @param array|Device|string $token
     * @param string $type
     */
    public function __construct($token, $type = self::TYPE_APNS)
    {
        parent::__construct($token, $type);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return '/3/device/' . $this->token;
    }
}