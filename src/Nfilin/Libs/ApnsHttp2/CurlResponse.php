<?php
namespace Nfilin\Libs\ApnsHttp2;

use Nfilin\Libs\MobileNotifications\Response\Curl as BaseCurlResponse;

/**
 * Class ApnsHttp2
 * @package Nfilin\Libs\MobileNotifications\Response
 */
class CurlResponse extends BaseCurlResponse
{
    /**
     * @var string
     */
    public $reason;
    /**
     * @var int
     */
    public $timestamp;

    /**
     * @param resource $ch
     * @return CurlResponse
     */
    public static function fromCurl($ch)
    {
        $resp = parent::fromCurl($ch);
        switch ($resp->status) {
            case 200:
                //Success
                break;
            case 400:
                //Bad request
                break;
            case 403:
                //There was an error with the certificate.
                break;
            case 405:
                //The request used a bad :method value. Only POST requests are supported.
                break;
            case 410:
                //The device token is no longer active for the topic.
                break;
            case 413:
                //The notification payload was too large.
                break;
            case 429:
                //The server received too many requests for the same device token.
                break;
            case 500:
                //Internal server error
                break;
            case 503:
                //The server is shutting down and unavailable.
                break;
            default:
                //UNKNOWN ERROR
        }
        return $resp;
    }

}