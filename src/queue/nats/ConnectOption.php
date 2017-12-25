<?php
/**
 * ConnectOption.php
 * Connect option
 * User: lixin
 * Date: 17-12-25
 */

namespace queue\nats;

class ConnectOption
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
    private $username = null;

    /**
     * Password
     * @var string
     */
    private $password = null;

    /**
     * Client development language
     * @var string
     */
    private $lang = 'php';

    /**
     * If reconnect mode is enabled.
     * @var boolean
     */
    private $reconnect = true;

    /**
     * Timeout
     * @var int
     */
    private $timeout = 0;

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
        'reconnect',
    ];

    /**
     * ConnectOption constructor.
     * @param array $options
     * @author lixin
     */
    public function __construct($options = [])
    {
        if (empty($options) === false) {
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
    public function setHost($host)
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
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }


    /**
     * Get username
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Set username
     * @param string $username
     * @return $this
     */
    public function setUser($username)
    {
        $this->username = $username;
        return $this;
    }


    /**
     * Get password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
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
    public function setTimeout($timeout = 0)
    {
        if ($timeout === 0) {
            $this->timeout = intval(ini_get('default_socket_timeout'));
        } else {
            $this->timeout = $timeout;
        }
        return $this;
    }

    /**
     * Get reconnect
     * @return boolean
     */
    public function isReconnect()
    {
        return $this->reconnect;
    }


    /**
     * Set reconnect
     * @param boolean $reconnect Reconnect flag
     * @return $this
     */
    public function setReconnect($reconnect)
    {
        $this->reconnect = $reconnect;
        return $this;
    }

    /**
     * Set the connection option
     * @param array $options Connect option config
     * @return void
     */
    public function setConnectionOptions($options)
    {
        $this->init($options);
    }

    /**
     * Get the options JSON string
     * @return string
     */
    public function __toString()
    {
        $a = [
            'lang' => $this->lang,
        ];
        if (empty($this->user) === false) {
            $a['user'] = $this->username;
        }

        if (empty($this->pass) === false) {
            $a['pass'] = $this->password;
        }

        return json_encode($a);
    }

    /**
     * Init connect option config
     * @param array $options Connect option config
     * @throws \Exception
     * @author lixin
     */
    protected function init($options)
    {
        if (is_array($options) === false) {
            throw new \Exception('The $options Can not be circulated');
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