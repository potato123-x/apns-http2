<?php
namespace Nfilin\Libs\ApnsHttp2;

use Nfilin\Libs\MobileNotifications\Authorization\AuthorizationInterface;
use Nfilin\Libs\MobileNotifications\Connection\Curl as CurlConnection;
use Nfilin\Libs\MobileNotifications\Message\MessageInterface;

/**
 * Class Connection
 * @package Nfilin\Libs\ApnsHttp2
 * @property string baseUrl
 */
class Connection extends CurlConnection
{
    const SERVER_SANDBOX = 'api.development.push.apple.com';
    const SERVER_PRODUCTION = 'api.push.apple.com';
    const PROTOCOL = 'https';
    const PORT = 443;

    /**
     * @var Certificate
     */
    private $auth;
    /**
     * @var bool
     */
    private $sandbox = true;

    /**
     * AppleHttp2 constructor.
     * @param Certificate|AuthorizationInterface $auth
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
     * @param Certificate|AuthorizationInterface $auth
     * @return $this
     * @throws \Exception
     */
    public function setAuthorization(AuthorizationInterface $auth)
    {
        if (!$auth instanceof Certificate) {
            throw new \Exception("Only APNS authorization supported");
        }
        $this->auth = $auth;
        return $this;
    }

    /**
     * @param Message|MessageInterface $message
     * @return Response
     * @throws \Exception
     */
    public function send(MessageInterface $message)
    {
        if (!($message instanceof Message)) {
            throw new \Exception("Receivers should be list od APNS devices");
        }
        /** @var Message $message */
        $receivers = $message->receivers;
        $results = new Response();
        foreach ($receivers as $id => $receiver) {
            /** @var Device $receiver */
            $this->connect();
            curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl . $receiver->path);
            $data = $message->json();
            $headers = [];
            $headers['content-length'] = strlen($data);
            $headers['apns-topic'] = $message->topic;
            $headers['apns-expiration'] = $message->time_to_live ? (int)$message->time_to_live + time() : 0;
            switch ($message->priority) {
                case 5:
                case 'normal':
                    $headers['apns-priority'] = 5;
                    break;
                case 10:
                case 'high':
                    $headers['apns-priority'] = 10;
                    break;
                default:
                    $headers['apns-priority'] = 10;
            }

            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

            $results[] = [$receiver, CurlResponse::fromCurl($this->curl)];

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