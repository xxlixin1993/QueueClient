<?php
/**
 * request.php
 * request
 * User: lixin
 * Date: 17-12-22
 */

require_once __DIR__ . '/../vendor/autoload.php';

$natsClient = \LQueue\Factory::getQueue('nats');

try {
//    $natsClient->getConnectOption()->setUser('derek')->setPass('T0pS3cr3t')->setPort(4242);
    $natsClient->driver();
    
    $sid = $natsClient->subscribe(
        'foo',
        function ($response) {
            $response->reply('Reply: Hello, ' . $response->getBody() . ' ^_^!');
        }
    );

    $natsClient->request(
        'foo',
        'bar',
        function ($response) {
            echo $response->getBody();
        }
    );
} catch (\Exception $e) {
    echo $e->getCode();
    echo $e->getMessage();
}
