<?php
/**
 * pub.php
 * public
 * User: lixin
 * Date: 17-12-22
 */
require_once __DIR__.'/../vendor/autoload.php';
$client = new \queue\Factory();

$natsClient = $client->getQueue('nats');

try{
//    $natsClient->getConnectOption()->setUser('derek')->setPass('T0pS3cr3t')->setPort(4242);

    $natsClient->driver();
    $natsClient->publish('FOO', 'bar', 11);
}catch(\Exception $e){
    echo $e->getCode();
    echo $e->getMessage();
}

