<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class NatsQueueTest extends TestCase
{
    /**
     * Test getConnectOption function
     */
    public function testGetConnectOption()
    {
        $options = new \LQueue\nats\ConnectOption();
        $client = new \LQueue\nats\NatsQueue($options);
        $this->assertEquals($client->getConnectOption(), $options);
    }

    /**
     * Test Nasts driver
     * default config hostname is localhost and port is 4222
     */
    public function testDriver()
    {
        $options = new \LQueue\nats\ConnectOption();
        $client = new \LQueue\nats\NatsQueue($options);
        $client->driver();
        $this->assertTrue($client->isConnected());

        $client->close();
        $this->assertFalse($client->isConnected());
    }

    /**
     * Test Publish
     */
    public function testPublish()
    {
        $options = new \LQueue\nats\ConnectOption();
        $client = new \LQueue\nats\NatsQueue($options);
        $client->driver();
        $client->publish('foo', 'bar');
        $count = $client->getPubCount();
        $this->assertInternalType('int', $count);
        $this->assertEquals(1, $count);
    }
}