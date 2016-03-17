# apns-http2

**Requires Curl with HTTP2 and SSL support.**


Usage example:
```php
<?php
    use Nfilin\Libs\ApnsHttp2 as Apns;

    /* Create APNS certificate */
    $certificate = new Apns\Certificate('/path/to/certificate', 'pass_phrase');

    /* Create connection */
    $connection = new Apns\Connection($certificate, [ 'sandbox' => false  ]);

    /* Create payload */
    $payload = new Apns\Payload();
    $payload->title = 'Alert title';
    $payload->custom_data['some_custom_data_key'] = 'some data';
    $payload->badge = 1;

    /* Wrap list of receivers to recognizable format */
    $receivers = new Apns\DeviceList(
                [
                    'notification_id_of_first_receiver',
                    'notification_id_of_second_receiver',
                    //...
                ]
            );

    /* Create message for seklected receivers and payload */
    $message = new Apns\Message($receivers, $payload);

    /* Sign message with topic valid for selected receivers */
    $message->topic = 'some.example.app';

    /* Set expire time in seconds from current time */
    $message->time_to_live = 3600; // One hour after been sent

    /* Send notifications and get responses */
    $response = $connection->send($message);
```