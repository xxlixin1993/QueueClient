<?php
/**
 * Queue.php
 * redis队列
 * User: lixin
 * Date: 17-12-25
 */

namespace queue\redis;


use queue\ErrorCode;
use queue\interfaces\IQueue;

class RedisQueue implements IQueue
{
    /**
     * Redis
     * @var mixed
     */
    private $conn;

    /**
     * Redis config
     * @var mixed
     */
    private $options;

    public function __construct(ConnectOption $options = null)
    {
        if ($options === null) {
            $this->options = new ConnectOption();
        } else {
            $this->options = $options;
        }
    }

    /**
     * Get Redis Connect config
     * @return ConnectOption
     * @author lixin
     */
    public function getConnectOption()
    {
        return $this->options;
    }

    /**
     * Set a queue driver
     * @param int $timeout Redis connect timeout
     * @return void
     * @throws \Exception
     * @author lixin
     */
    public function driver(int $timeout = 0)
    {
        if ($timeout === 0) {
            $timeout = $this->options->getTimeout();
        }

        $this->conn = new \Redis();
        $connRes = $this->conn->pconnect($this->options->getHost(), $this->options->getPort(), $timeout);

        if (!$connRes) {
            throw new \Exception('Can not connect Redis', ErrorCode::CONNECT_ERROR);
        }

        $password = $this->options->getPass();
        if (!empty($password)) {
            $authRes = $this->conn->auth($password);
            if (!$authRes) {
                throw new \Exception('Cant not auth Redis', ErrorCode::CONNECT_OPTIONS_ERROR);
            }
        }
    }

    /**
     * Publish
     * @param string $subject
     * @param string $data
     * @param string $inbox Unsupported
     * @throws \Exception
     * @return void
     */
    public function publish(string $subject, string $data, string $inbox = '')
    {
        $this->conn->publish($subject, $data);
    }

    /**
     * Request does a request and executes a callback with the response
     * @param string $subject
     * @param string $data
     * @param \Closure $callback
     * @throws \Exception
     * @return void
     */
    public function request(string $subject, string $data, \Closure $callback)
    {
        // TODO: Implement request() method.
    }

    /**
     * Subscribe
     * @param string $subject
     * @param \Closure $callback
     * @throws \Exception
     * @return string
     */
    public function subscribe(string $subject, \Closure $callback)
    {
        // TODO: Implement subscribe() method.
    }

    /**
     * Wait for message return
     * @param int $msgNumber
     * @throws \Exception
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
        if ($this->conn === null) {
            return false;
        }

        $result = $this->conn->close();
        $this->conn = null;
        return $result;
    }
}