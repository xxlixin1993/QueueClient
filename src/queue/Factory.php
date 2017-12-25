<?php
/**
 * Factory.php
 * 队列工厂
 * User: lixin
 * Date: 17-12-22
 */
namespace queue;

use queue\interfaces\IQueue;
use queue\nats\NatsQueue;
use queue\redis\RedisQueue;

class Factory
{
    /**
     * Get Queue object
     * @param string $type
     * @return IQueue
     * @throws \Exception
     * @author lixin
     */
    public static function getQueue(string $type) : IQueue
    {
        switch ($type) {
            case 'redis':
                //TODO config
                return new RedisQueue();
                break;
            case 'nats':
                //TODO config
                return new NatsQueue();
                break;
            default:
                throw new \Exception('Unsupported driver');
                break;
        }
    }
}