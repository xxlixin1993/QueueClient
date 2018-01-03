<?php
/**
 * ConnectOption.php
 * 描述
 * User: lixin
 * Date: 17-12-26
 */

namespace LQueue\redis;


use LQueue\ErrorCode;
use LQueue\interfaces\IOption;

class ConnectOption implements IOption
{
    /**
     * Host
     * @var string
     */
    private $host = 'localhost';

    /**
     * Port
     * @var integer
     */
    private $port = 6379;

    /**
     * Password
     * @var string
     */
    private $password = '';

    /**
     * Timeout
     * @var int
     */
    private $timeout = 3;

    /**
     * Allows to define parameters
     * @var array
     */
    private $configurable = [
        'host',
        'port',
        'password',
        'timeout',
    ];

    /**
     * ConnectOption constructor.
     * @param array $options
     * @author lixin
     */
    public function __construct($options = [])
    {
        if (!empty($options) === true) {
            $this->init($options);
        }
    }

    /**
     * Get host
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set host
     * @param string $host Host
     * @return $this
     */
    public function setHost(string $host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Get port
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set port
     * @param int $port Port
     * @return $this
     */
    public function setPort(int $port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * Set Username
     * @param string $user
     * @return $this
     */
    public function setUser(string $user)
    {
        // TODO: Implement setUser() method.
    }

    /**
     * Get Username
     * @return string
     */
    public function getUser()
    {
        // TODO: Implement getUser() method.
    }

    /**
     * Get password
     * @return string
     */
    public function getPass()
    {
        return $this->password;
    }

    /**
     * Set password
     * @param string $password
     * @return $this
     */
    public function setPass(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get timeout
     * @return int
     * @author lixin
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set timeout
     * @param int $timeout Timeout
     * @return $this
     */
    public function setTimeout(int $timeout = 0)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Set the connection option
     * @param array $options Connect option config
     * @return void
     */
    public function setConnectionOptions(array $options)
    {
        $this->init($options);
    }

    /**
     * Get the options JSON string
     * @return string
     */
    public function __toString()
    {
        $option = [
            'host' => $this->host,
            'port' => $this->port,
            'timeout' => $this->timeout,
        ];
        if (!empty($this->pass)) {
            $option['password'] = $this->password;
        }
        return json_encode($option);
    }

    /**
     * Init connect option config
     * @param array $options Connect option config
     * @throws \Exception
     * @author lixin
     */
    protected function init(array $options)
    {
        if (is_array($options) === false) {
            throw new \Exception('The $options Can not be circulated', ErrorCode::CONNECT_OPTIONS_ERROR);
        }

        foreach ($options as $key => $value) {
            if (in_array($key, $this->configurable, true) === false) {
                continue;
            }

            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method) === true) {
                $this->$method($value);
            }
        }
    }
}