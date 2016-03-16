<?php
namespace Nfilin\Libs\ApnsHttp2;

use Nfilin\Libs\MobileNotifications\Device\Base as BaseDevice;
use Nfilin\Libs\MobileNotifications\Device\DeviceInterface;

/**
 * Class Device
 * @package Nfilin\Libs\ApnsHttp2 *
 * @property string|null path
 */
class Device extends BaseDevice
{
    public $type = self::TYPE_APNS;

    /**
     * Apns constructor.
     * @param array|DeviceInterface|string $token
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