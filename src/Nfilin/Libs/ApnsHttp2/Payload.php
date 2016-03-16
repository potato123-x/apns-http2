<?php
namespace Nfilin\Libs\ApnsHttp2;

use Nfilin\Libs\MobileNotifications\Payload\Base as BasePayload;

/**
 * Class Payload
 * @package Nfilin\Libs\ApnsHttp2
 */
class Payload extends BasePayload
{
    /**
     * @var int
     */
    public $content_available;
    /**
     * @var string|null
     */
    public $action_loc_key;
    /**
     * @var string
     */
    public $launch_image;

    /**
     * @return array
     */
    function jsonSerialize()
    {
        $ret = [];
        $ret['alert'] = [];
        $ret['badge'] = $this->badge;
        $ret['sound'] = $this->sound;
        $ret['category'] = $this->click_action;

        $ret['alert']['title'] = $this->title;
        $ret['alert']['body'] = $this->body;
        $ret['alert']['title-loc-key'] = $this->title_loc_key;
        $ret['alert']['title-loc-args'] = $this->title_loc_args;
        $ret['alert']['action-loc-key'] = $this->action_loc_key;
        $ret['alert']['loc-key'] = $this->body_loc_key;
        $ret['alert']['loc-args'] = $this->body_loc_args;
        $ret['alert']['launch-image'] = $this->launch_image;
        foreach ($this->custom_data as $key => $val) {
            $ret[$key] = $val;
        }
        return $ret;
    }

}