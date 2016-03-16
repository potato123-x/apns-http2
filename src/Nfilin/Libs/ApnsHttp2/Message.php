<?php

namespace Nfilin\Libs\ApnsHttp2;

use Nfilin\Libs\MobileNotifications\Device\DeviceListInterface;
use Nfilin\Libs\MobileNotifications\Message\Base as BaseMessage;
use Nfilin\Libs\MobileNotifications\Payload\PayloadInterface;

/**
 * Class Message
 * @package Nfilin\Libs\ApnsHttp2
 * @property string topic
 */
class Message extends BaseMessage
{
    /**
     * ApnsHttp2 constructor.
     * @param DeviceList|DeviceListInterface $receivers
     * @param Payload|PayloadInterface|null $payload
     * @throws \Exception
     */
    public function __construct(DeviceListInterface $receivers, PayloadInterface $payload = null)
    {
        if (!$receivers instanceof DeviceList) {
            throw new \Exception('Only APNS devices allowed for APNS HTTP/2 message');
        }
        parent::__construct($receivers, $payload);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $ret = Payload::wrap($this->payload)->jsonSerialize();
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

    /**
     * @param $value
     */
    public function setTopic($value)
    {
        $this->restricted_package_name = $value;
    }
}