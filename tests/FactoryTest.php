<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{

    public function testGetRedisQueue()
    {
        $this->assertInstanceOf(
            \LQueue\redis\RedisQueue::class,
            \LQueue\Factory::getQueue('redis')
        );

    }

    public function testGetNatsQueue()
    {
        $this->assertInstanceOf(
            \LQueue\nats\NatsQueue::class,
            \LQueue\Factory::getQueue('nats')
        );
    }

    public function testGetOtherQueue()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Unsupported driver');
        $this->expectExceptionCode(\LQueue\ErrorCode::UNSUPPORTED_DRIVER);
        \LQueue\Factory::getQueue('other');
    }
}
