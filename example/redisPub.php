<?php
/**
 * redisPub.php
 * redis pub 
 * User: lixin
 * Date: 17-12-26
 */
require_once __DIR__.'/../vendor/autoload.php';
$client = new \queue\Factory();

$redisClient = $client->getQueue('redis');

try{
    $redisClient->driver();
    $redisClient->publish('FOO', 'bar');
}catch(\Exception $e){
    echo $e->getCode();
    echo $e->getMessage();
}

