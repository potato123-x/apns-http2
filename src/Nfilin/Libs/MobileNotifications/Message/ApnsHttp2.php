<?php

namespace Nfilin\Libs\MobileNotifications\Message;

use Nfilin\Libs\MobileNotifications\Device\ApnsList as dApnsList;
use Nfilin\Libs\MobileNotifications\Device\DeviceListInterface;
use Nfilin\Libs\MobileNotifications\Payload\Apns as pApns;
use Nfilin\Libs\MobileNotifications\Payload\PayloadInterface;

/**
 * Class ApnsHttp2
 * @property string topic
 * @package Nfilin\Libs\MobileNotifications\Message
 */
class ApnsHttp2 extends Base
{
    /**
     * ApnsHttp2 constructor.
     * @param dApnsList|DeviceListInterface $receivers
     * @param pApns|PayloadInterface|null $payload
     * @throws \Exception
     */
    public function __construct(DeviceListInterface $receivers, PayloadInterface $payload = null)
    {
        if (!$receivers instanceof dApnsList) {
            throw new \Exception('Only APNS devices allowed for APNS HTTP/2 message');
        }
        parent::__construct($receivers, $payload);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $ret = pApns::wrap($this->payload)->jsonSerialize();
        $ret['content-available'] = $this->content_available;
        return ['aps' => $ret];
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->restricted_package_name;
    }
}