<?php
/**
 * Queue.php
 * redis队列
 * User: lixin
 * Date: 17-12-25
 */

namespace queue\redis;


use queue\interfaces\IQueue;

class RedisQueue implements IQueue{

    /**
     * Set a queue driver
     * @param int $timeout Socket timeout
     * @return mixed
     */
    public function driver(int $timeout = 0)
    {
        // TODO: Implement driver() method.
    }

    /**
     * Publish
     * @param string $subject
     * @param string $data
     * @param int $inbox
     * @return mixed
     */
    public function publish(string $subject, string $data, int $inbox = 0)
    {
        // TODO: Implement publish() method.
    }

    /**
     * Request does a request and executes a callback with the response
     * @param string $subject
     * @param string $data
     * @param \Closure $callback
     * @return mixed
     */
    public function request(string $subject, string $data, \Closure $callback)
    {
        // TODO: Implement request() method.
    }

    /**
     * Subscribe
     * @param string $subject
     * @param \Closure $callback
     * @return mixed
     */
    public function subscribe(string $subject, \Closure $callback)
    {
        // TODO: Implement subscribe() method.
    }

    /**
     * Wait for message return
     * @param int $msgNumber
     * @return mixed
     */
    public function wait(int $msgNumber)
    {
        // TODO: Implement wait() method.
    }

    /**
     * Close socket
     * @return mixed
     */
    public function close()
    {
        // TODO: Implement close() method.
    }
}