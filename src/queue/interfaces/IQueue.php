<?php

/**
 * Interface Queue
 */
namespace queue\interfaces;

interface IQueue
{
    /**
     * Set a queue driver
     * @param int $timeout Socket timeout
     * @return mixed
     */
    public function driver(int $timeout = 0);

    /**
     * Publish
     * @param string $subject
     * @param string $data
     * @param string $inbox
     * @return mixed
     */
    public function publish(string $subject, string $data,string $inbox = '');

    /**
     * Request does a request and executes a callback with the response
     * @param string $subject
     * @param string $data
     * @param \Closure $callback
     * @return mixed
     */
    public function request(string $subject, string $data, \Closure $callback);

    /**
     * Subscribe
     * @param string $subject
     * @param \Closure $callback
     * @return mixed
     */
    public function subscribe(string $subject, \Closure $callback);

    /**
     * Wait for message return
     * @param int $msgNumber
     * @return mixed
     */
    public function wait(int $msgNumber);

    /**
     * Close socket
     * @return mixed
     */
    public function close();

    /**
     * Get connect config
     * @return mixed
     */
    public function getConnectOption();
}