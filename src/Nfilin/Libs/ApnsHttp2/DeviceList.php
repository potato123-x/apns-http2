<?php
namespace Nfilin\Libs\ApnsHttp2;

use Nfilin\Libs\MobileNotifications\Device\BaseList as BaseDeviceList;
use Nfilin\Libs\MobileNotifications\Device\DeviceListInterface;

/**
 * Class DeviceList
 * @package Nfilin\Libs\ApnsHttp2
 */
class DeviceList extends BaseDeviceList
{
    /**
     * DeviceList constructor.
     * @param array|DeviceList|DeviceListInterface $array
     */
    public function __construct($array = [])
    {
        parent::__construct($array, Device::className());
    }

}