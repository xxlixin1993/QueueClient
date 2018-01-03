<?php

/**
 * Interface Queue
 */
namespace LQueue\interfaces;

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
     * Adds the string value to the head (left) of the list
     * @param string $subject
     * @param string $data
     * @return mixed
     * @author lixin
     */
    public function enQueue(string $subject, string $data);

    /**
     * Pops a value from the tail of a list,
     * @param string $subject
     * @return mixed
     * @author lixin
     */
    public function deQueue(string $subject);

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