<?php
/**
 * pubsub.php
 * pub and sub
 * User: lixin
 * Date: 17-12-25
 */

require_once __DIR__ . '/../vendor/autoload.php';

$natsClient = \LQueue\Factory::getQueue('nats');

try {
    $natsClient->driver();
    $natsClient->subscribe('FOO', function ($response) {
        printf("Data: %s\r\n", $response->getBody());
    });
    
    $natsClient->publish('FOO', 'bar');

    // Wait for 1 message.
    $natsClient->wait(1);

    $natsClient->close();
} catch (\Exception $e) {
    echo $e->getCode();
    echo $e->getMessage();
}

