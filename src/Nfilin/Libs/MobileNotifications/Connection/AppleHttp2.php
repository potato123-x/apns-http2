<?php

namespace Nfilin\Libs\MobileNotifications\Connection;

use Nfilin\Libs\MobileNotifications\Authorization\Apns as aApns;
use Nfilin\Libs\MobileNotifications\Authorization\AuthorizationInterface;
use Nfilin\Libs\MobileNotifications\Device\Apns as dApns;
use Nfilin\Libs\MobileNotifications\Message\ApnsHttp2 as mApnsHttp2;
use Nfilin\Libs\MobileNotifications\Message\MessageInterface;
use Nfilin\Libs\MobileNotifications\Response\ApnsBatch;
use Nfilin\Libs\MobileNotifications\Response\ApnsHttp2Single as rApnsHttp2;

/**
 * Class AppleHttp2
 * @package Nfilin\Libs\MobileNotifications\Connection
 * @property $baseUrl string
 */
class AppleHttp2 extends Curl
{
    const SERVER_SANDBOX = 'api.development.push.apple.com';
    const SERVER_PRODUCTION = 'api.push.apple.com';
    const PROTOCOL = 'https';
    const PORT = 443;

    /**
     * @var aApns
     */
    private $auth;
    /**
     * @var bool
     */
    private $sandbox = true;

    /**
     * AppleHttp2 constructor.
     * @param aApns|AuthorizationInterface $auth
     * @param array $options
     */
    public function __construct(AuthorizationInterface $auth, $options = [])
    {
        parent::__construct($auth, $options);
        $this->sandbox = empty($options['sandbox']) ? false : $options['sandbox'];
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return sprintf('%s://%s:%d', self::PROTOCOL, $this->sandbox ? self::SERVER_SANDBOX : self::SERVER_PRODUCTION, self::PORT);
    }

    /**
     * @param aApns|AuthorizationInterface $auth
     * @return $this
     * @throws \Exception
     */
    public function setAuthorization(AuthorizationInterface $auth)
    {
        if (!$auth instanceof aApns) {
            throw new \Exception("Only APNS authorization supported");
        }
        $this->auth = $auth;
        return $this;
    }

    /**
     * @param mApnsHttp2|MessageInterface $message
     * @return ApnsBatch
     * @throws \Exception
     */
    public function send(MessageInterface $message)
    {
        if (!($message instanceof mApnsHttp2)) {
            throw new \Exception("Receivers should be list od APNS devices");
        }
        /** @var mApnsHttp2 $message */
        $receivers = $message->receivers;
        $results = new ApnsBatch();
        foreach ($receivers as $id => $receiver) {
            /** @var dApns $receiver */
            $this->connect();
            curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl . $receiver->path);
            $data = $message->json();
            $headers = [];
            $headers['content-length'] = strlen($data);
            $headers['apns-topic'] = $message->topic;

            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

            $results[] = rApnsHttp2::fromCurl($this->curl);

            $this->close();
        }
        return $results;
    }

    /**
     * @return $this
     */
    public function connect()
    {
        parent::connect();
        $ch = $this->curl;
        curl_setopt($ch, CURLOPT_HTTP_VERSION, 3);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->auth->certificate);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->auth->passPhrase);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->auth->certificate);
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $this->auth->passPhrase);
        return $this;
    }
}