<?php
/**
 * pubsub.php
 * pub and sub
 * User: lixin
 * Date: 17-12-25
 */

require_once __DIR__ . '/../vendor/autoload.php';
$client = new \queue\Factory();

$natsClient = $client->getQueue('nats');

try {
    $natsClient->driver();
    $natsClient->subscribe('FOO', function ($msg) {
        printf("Data: %s\r\n", $msg->getBody());
    });
    
    $natsClient->publish('FOO', 'bar');

    // Wait for 1 message.
    $natsClient->wait(1);

    $natsClient->close();
} catch (\Exception $e) {
    echo $e->getCode();
    echo $e->getMessage();
}

