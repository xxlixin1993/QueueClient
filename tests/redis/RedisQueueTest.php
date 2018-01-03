<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class RedisQueueTest extends TestCase
{
    /**
     * Test getConnectOption function
     */
    public function testGetConnectOption()
    {
        $options = new \LQueue\redis\ConnectOption();
        $client = new \LQueue\redis\RedisQueue($options);
        $this->assertEquals($client->getConnectOption(), $options);
    }

    /**
     * Test Redis driver
     * default config hostname is localhost and port is 6379
     */
    public function testDriver()
    {
        $options = new \LQueue\redis\ConnectOption();
        $client = new \LQueue\redis\RedisQueue($options);
        $client->driver();
        $this->assertTrue($client->isConnected());

        $client->close();
        $this->assertFalse($client->isConnected());
    }
}