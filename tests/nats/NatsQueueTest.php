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

    /**
     * Test subscribe
     */
    public function testSubscribe()
    {
        $this->expectOutputString("Data: bar\r\n");
        $options = new \LQueue\nats\ConnectOption();
        $client = new \LQueue\nats\NatsQueue($options);
        $client->driver();

        $client->subscribe('foo', function ($response) {
            printf("Data: %s\r\n", $response->getBody());
        });
        $client->publish('foo', 'bar');

        // Wait for 1 message.
        $client->wait(1);
    }

    /**
     * Test request
     */
    public function testRequest()
    {
        $options = new \LQueue\nats\ConnectOption();
        $client = new \LQueue\nats\NatsQueue($options);
        $client->driver();
        for ($i = 0; $i < 100; $i++) {
            $client->subscribe(
                'Hello' . $i,
                function ($res) {
                    $res->reply('Hello, ' . $res->getBody());
                }
            );

            $client->request(
                'Hello' . $i,
                'Data',
                function ($res) {
                    $this->assertEquals('Hello, Data', $res->getBody());
                }
            );
        }
    }
}