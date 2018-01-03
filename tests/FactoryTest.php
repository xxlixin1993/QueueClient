<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * Test get Redis queue object
     */
    public function testGetRedisQueue()
    {
        $this->assertInstanceOf(
            \LQueue\redis\RedisQueue::class,
            \LQueue\Factory::getQueue('redis')
        );

    }

    /**
     * Test get Nats queue object
     */
    public function testGetNatsQueue()
    {
        $this->assertInstanceOf(
            \LQueue\nats\NatsQueue::class,
            \LQueue\Factory::getQueue('nats')
        );
    }

    /**
     * Test get other queue object
     */
    public function testGetOtherQueue()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Unsupported driver');
        $this->expectExceptionCode(\LQueue\ErrorCode::UNSUPPORTED_DRIVER);
        \LQueue\Factory::getQueue('other');
    }
}
