<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * Test set and get function
     */
    public function testSettingAndGetting()
    {
        $options = new \LQueue\nats\ConnectOption();
        $client = new \LQueue\nats\NatsQueue($options);
        $client->driver();
        $response = new \LQueue\nats\Response('foo', 'bar', 'sid', $client);

        $this->assertEquals('foo', $response->getSubject());
        $this->assertEquals('bar', $response->getBody());
        $this->assertEquals('bar', $response->__toString());
        $this->assertEquals('sid', $response->getSid());
        $this->assertEquals($client, $response->getConn());
    }
}