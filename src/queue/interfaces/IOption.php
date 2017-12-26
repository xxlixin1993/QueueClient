<?php
/**
 * IOption.php
 * Connect option interface
 * User: lixin
 * Date: 17-12-26
 */

namespace queue\interfaces;


interface IOption
{
    /**
     * Get Host
     * @return string
     */
    public function getHost();

    /**
     * Set Host
     * @param string $host
     * @return $this
     */
    public function setHost(string $host);

    /**
     * Get Port
     * @return int
     */
    public function getPort();

    /**
     * Set Port
     * @param int $port
     * @return $this
     */
    public function setPort(int $port);

    /**
     * Get Username
     * @return string
     */
    public function getUser();

    /**
     * Set Username
     * @param string $user
     * @return $this
     */
    public function setUser(string $user);

    /**
     * Get Password
     * @return string
     */
    public function getPass();

    /**
     * Set Password
     * @param string $pass
     * @return $this
     */
    public function setPass(string $pass);

    /**
     * Get Timeout
     * @return int
     */
    public function getTimeout();

    /**
     * Set Timeout
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $timeout = 0);

    /**
     * Get connect option
     * @param array $option
     * @return void
     */
    public function setConnectionOptions(array $option);

    /**
     * Get the options JSON string
     * @return string
     */
    public function __toString();
}