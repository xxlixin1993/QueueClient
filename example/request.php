<?php
/**
 * Created by PhpStorm.
 * User: lixin
 * Date: 2017/12/25
 * Time: 下午7:32
 */

require_once __DIR__ . '/../vendor/autoload.php';
$client = new \queue\Factory();

$natsClient = $client->getQueue('nats');

try {
    $natsClient->driver();


    // Request Response.
    // Responding to requests.
    $sid = $natsClient->subscribe(
        'foo',
        function ($message) {
            $message->reply('Reply: Hello, ' . $message->getBody() . ' !!!');
        }
    );

    // Request.
    $natsClient->request(
        'foo',
        'bar',
        function ($message) {
            echo $message->getBody();
        }
    );
} catch (\Exception $e) {
    echo $e->getCode();
    echo $e->getMessage();
}
