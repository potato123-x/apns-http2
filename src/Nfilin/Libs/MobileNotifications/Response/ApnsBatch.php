<?php

namespace Nfilin\Libs\MobileNotifications\Response;

use Nfilin\Libs\BaseIterator;
use Nfilin\Libs\MobileNotifications\Device\DeviceInterface;
use Nfilin\Libs\MobileNotifications\Device\DeviceListInterface;

/**
 * Class ApnsBatch
 * @package Nfilin\Libs\MobileNotifications\Response
 */
class ApnsBatch extends BaseIterator implements ResponseInterface
{
    /**
     * @param $value
     */
    public function append($value)
    {
        parent::append(self::wrap($value));
    }

    /**
     * @param $value
     * @return array
     */
    protected static function wrap($value)
    {
        if (is_array($value)) {
            $new_val = [];
            foreach ($value as $val) {
                if (!is_object($val)) {
                    $new_val[] = $val;
                } elseif ($val instanceof ResponseInterface) {
                    $new_val['response'] = $val;
                } elseif ($val instanceof DeviceInterface) {
                    $new_val['device'] = $val;
                } elseif ($val instanceof DeviceListInterface) {
                    $new_val['device_list'] = $val;
                } else {
                    $new_val[] = $val;
                }
            }
        } else {
            $new_val = $value;
        }
        return $new_val;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        parent::offsetSet($offset, self::wrap($value));
    }
}