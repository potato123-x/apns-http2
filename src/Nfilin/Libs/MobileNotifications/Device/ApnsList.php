<?php

namespace Nfilin\Libs\MobileNotifications\Device;

/**
 * Class ApnsList
 * @package Nfilin\Libs\MobileNotifications\Device
 */
class ApnsList extends DeviceList
{
    /**
     * ApnsList constructor.
     * @param array|DeviceList|ApnsList $array
     */
    public function __construct($array = [])
    {
        parent::__construct($array, Apns::className());
    }

}