<?php
/**
 * ConnectOption.php
 * Connect option
 * User: lixin
 * Date: 17-12-25
 */

namespace LQueue\nats;

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
    private $port = 4222;

    /**
     * Username
     * @var string
     */
    private $user = null;

    /**
     * Password
     * @var string
     */
    private $pass = null;

    /**
     * Token to connect
     * @var string
     */
    private $token = null;

    /**
     * Client development language
     * @var string
     */
    private $lang = 'php';

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
        'user',
        'pass',
        'token',
        'lang',
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
     * Get Address
     * @return string
     */
    public function getAddress()
    {
        return 'tcp://' . $this->host . ':' . $this->port;
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
     * Get user
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     * @param string $user
     * @return $this
     */
    public function setUser(string $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get password
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set pass
     * @param string $pass
     * @return $this
     */
    public function setPass(string $pass)
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     * Get token
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token
     * @param string $token Token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get language
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set language
     * @param string $lang Language
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
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
            'lang' => $this->lang,
        ];
        if (!empty($this->user)) {
            $option['user'] = $this->user;
        }

        if (!empty($this->pass)) {
            $option['pass'] = $this->pass;
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