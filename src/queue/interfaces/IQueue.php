<?php

/**
 * Interface Queue
 */
namespace queue\interfaces;

interface IQueue
{
    /**
     * Get connect config
     * @return IOption
     */
    public function getConnectOption();
    
    /**
     * Set a queue driver
     * @param int $timeout Socket timeout
     * @throws \Exception
     * @return void
     */
    public function driver(int $timeout = 0);

    /**
     * Publish
     * @param string $subject
     * @param string $data
     * @param string $inbox
     * @throws \Exception
     * @return void
     */
    public function publish(string $subject, string $data,string $inbox = '');

    /**
     * Request does a request and executes a callback with the response
     * @param string $subject
     * @param string $data
     * @param \Closure $callback
     * @throws \Exception
     * @return void
     */
    public function request(string $subject, string $data, \Closure $callback);

    /**
     * Subscribe
     * @param string $subject
     * @param \Closure $callback
     * @throws \Exception
     * @return string
     */
    public function subscribe(string $subject, \Closure $callback);

    /**
     * Wait for message return
     * @param int $msgNumber
     * @throws \Exception
     * @return mixed
     */
    public function wait(int $msgNumber);

    /**
     * Close socket
     * @return bool
     */
    public function close();
    
}