<?php
/**
 * Factory.php
 * 队列工厂
 * User: lixin
 * Date: 17-12-22
 */
namespace LQueue;

use LQueue\interfaces\IQueue;
use LQueue\nats\NatsQueue;
use LQueue\redis\RedisQueue;

class Factory
{
    /**
     * Get Queue object
     * @param string $type
     * @return IQueue
     * @throws \Exception
     * @author lixin
     */
    public static function getQueue(string $type): IQueue
    {
        switch ($type) {
            case 'redis':
                return new RedisQueue();
                break;
            case 'nats':
                return new NatsQueue();
                break;
            default:
                throw new \Exception('Unsupported driver', ErrorCode::UNSUPPORTED_DRIVER);
                break;
        }
    }
}