<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class RedisQueueTest extends TestCase
{
    public function testGetConnectOption()
    {
        $options = new \LQueue\redis\ConnectOption();
        $client = new \LQueue\redis\RedisQueue($options);
        $this->assertEquals($client->getConnectOption(), $options);
    }
}