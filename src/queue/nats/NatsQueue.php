<?php

/**
 * NatsQueue.php
 * Manage Nats Queue.
 * User: lixin
 * Date: 17-12-22
 */
namespace queue\nats;

use queue\ErrorCode;
use queue\interfaces\IQueue;

class NatsQueue implements IQueue
{
    /**
     * Subscriptions
     * @var array
     */
    private $subscriptions = [];

    /**
     * Server config
     * @var ServerInfo
     */
    private $serverInfo;

    /**
     * Number of PINGs
     * @var int
     */
    private $pings = 0;

    /**
     * Number of messages published
     * @var int
     */
    private $pubs = 0;

    /**
     * Chunk size in bytes to use when reading an stream of data
     * @var int
     */
    private $chunkSize = 1500;

    /**
     * Nats option config
     * @var ConnectOption
     */
    private $options;

    /**
     * Nats socket
     * @var mixed
     */
    private $socket;

    public function __construct(ConnectOption $options = null)
    {
        if ($options === null) {
            $this->options = new ConnectOption();
        } else {
            $this->options = $options;
        }
    }

    /**
     * Nats Queue Driver
     * @param int $timeout Socket timeout
     * @throws \Exception
     * @return void
     */
    public function driver(int $timeout = 0)
    {
        if ($timeout === 0) {
            $timeout = $this->options->getTimeout();
        }

        $this->socket = $this->getSocket($this->options->getAddress(), $timeout);

        $msg = 'CONNECT ' . $this->options;
        $this->send($msg);
        $connectResponse = $this->receive();

        if ($this->isErrorResponse($connectResponse) === true) {
            throw new \Exception('Connect error, Msg: ' . $connectResponse, ErrorCode::CONNECT_ERROR);
        } else {
            $this->processServerInfo($connectResponse);
        }

        $this->ping();
        $pingResponse = $this->receive();

        if ($this->isErrorResponse($pingResponse) === true) {
            throw new \Exception('Ping error, Msg: ' . $pingResponse, ErrorCode::PING_ERROR);
        }
    }

    /**
     * Send message
     * @param string $subject Queue name
     * @param string $data Send data
     * @param string $inbox The reply inbox subject that subscribers can use to send
     *                   a response back to the publisher/requestor
     * @return void
     * @throws \Exception
     * @author lixin
     */
    public function publish(string $subject, string $data,string $inbox = '')
    {
        $msg = 'PUB ' . $subject;
        if ($inbox !== '') {
            $msg = $msg . ' ' . $inbox;
        }

        $msg = $msg . ' ' . strlen($data);
        $this->send($msg . "\r\n" . $data);
        $this->pubs += 1;
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
        $sid = $this->uuid();
        $msg = 'SUB ' . $subject . ' ' . $sid;
        $this->send($msg);
        $this->subscriptions[$sid] = $callback;
        return $sid;
    }

    /**
     * Request does a request and executes a callback with the response
     * @param string $subject Message topic.
     * @param string $data Message data.
     * @param \Closure $callback Closure to be executed as callback.
     * @throws \Exception
     * @return void
     */
    public function request(string $subject, string $data, \Closure $callback)
    {
        $inbox = '_INBOX.' . $this->uuid();
        $sid = $this->subscribe(
            $inbox,
            $callback
        );
        $this->unSubscribe($sid, 1);
        $this->publish($subject, $data, $inbox);
        $this->wait(1);
    }

    /**
     * Wait message return
     * @param int $msgNumber Number of messages to wait for
     * @throws \Exception
     * @return $this|null
     */
    public function wait(int $msgNumber = 0)
    {
        $count = 0;
        $info = stream_get_meta_data($this->socket);

        while (is_resource($this->socket) === true && feof($this->socket) === false && empty($info['timed_out']) === true) {
            $line = $this->receive();

            if ($line === false) {
                return null;
            }

            if (strpos($line, 'PING') === 0) {
                $this->handlePING();
            }

            if (strpos($line, 'MSG') === 0) {
                $count++;
                $this->handleMSG($line);
                if (($msgNumber !== 0) && ($count >= $msgNumber)) {
                    return $this;
                }
            }

            $info = stream_get_meta_data($this->socket);
        }

        $this->close();

        return $this;
    }

    /**
     * Get Nats Connect option
     * @return ConnectOption
     * @author lixin
     */
    public function getConnectOption()
    {
        return $this->options;
    }

    /**
     * Close socket
     * @return bool
     * @author lixin
     */
    public function close()
    {
        if ($this->socket === null) {
            return false;
        }

        $result = fclose($this->socket);
        $this->socket = null;
        return $result;
    }

    /**
     * Send message
     * @param string $data Send data
     * @throws \Exception
     * @author lixin
     */
    private function send(string $data)
    {
        $msg = $data . "\r\n";
        $len = strlen($msg);
        while (true) {
            $written = @fwrite($this->socket, $msg);
            if ($written === false) {
                throw new \Exception('Sending data error', ErrorCode::SEND_DATA_ERROR);
            }

            if ($written === 0) {
                throw new \Exception('Can not input anything in socket', ErrorCode::SOCKET_ERROR);
            }

            $len = ($len - $written);
            if ($len > 0) {
                $msg = substr($msg, (0 - $len));
            } else {
                break;
            }
        }
    }

    /**
     * Sends PING message.
     * @return void
     */
    private function ping()
    {
        $msg = 'PING';
        $this->send($msg);
        $this->pings += 1;
    }

    /**
     * Receive data
     * @param int $len Number of bytes to receive
     * @return null|string
     * @author lixin
     */
    private function receive($len = 0)
    {
        if ($len > 0) {
            $chunkSize = $this->chunkSize;
            $line = null;
            $receivedBytes = 0;
            while ($receivedBytes < $len) {
                $bytesLeft = ($len - $receivedBytes);
                if ($bytesLeft < $this->chunkSize) {
                    $chunkSize = $bytesLeft;
                }

                $readChunk = fread($this->socket, $chunkSize);
                $receivedBytes += strlen($readChunk);
                $line .= $readChunk;
            }
        } else {
            $line = fgets($this->socket);
        }

        return $line;
    }

    /**
     * Unsubscribe from a event given a subject.
     * @param string $sid Subscription ID.
     * @param int $quantity Quantity of messages.
     * @return void
     */
    private function unSubscribe($sid, $quantity = 0)
    {
        $msg = 'UNSUB ' . $sid;
        if ($quantity !== 0) {
            $msg = $msg . ' ' . $quantity;
        }

        $this->send($msg);
        if ($quantity === 0) {
            unset($this->subscriptions[$sid]);
        }
    }

    /**
     * Handles PING command
     * @return void
     */
    private function handlePING()
    {
        $this->send('PONG');
    }

    /**
     * Handles MSG command.
     * @param $line
     * @throws \Exception
     */
    private function handleMSG($line)
    {
        $parts = explode(' ', $line);
        $subject = null;
        $length = trim($parts[3]);
        $sid = $parts[2];

        if (count($parts) === 5) {
            $length = trim($parts[4]);
            $subject = $parts[3];
        } else if (count($parts) === 4) {
            $length = trim($parts[3]);
            $subject = $parts[1];
        }

        $payload = $this->receive($length);

        $msg = new Response($subject, $payload, $sid, $this);

        if (isset($this->subscriptions[$sid]) === false) {
            throw new \Exception('Callback function can not be found', ErrorCode::CALLBACK_FUNCTION_ERROR);
        }

        $func = $this->subscriptions[$sid];
        if (is_callable($func) === true) {
            $func($msg);
        } else {
            throw new \Exception('Func can not callback', ErrorCode::CALLBACK_FUNCTION_ERROR);
        }
    }

    /**
     * Create socket
     * @param string $address
     * @param int $timeout
     * @return resource
     * @throws \Exception
     * @author lixin
     */
    private function getSocket($address, $timeout)
    {
        $fp = stream_socket_client($address, $errNo, $errString, $timeout, STREAM_CLIENT_CONNECT);

        if ($fp === false) {
            throw new \Exception('Create socket error: ' . $errString, ErrorCode::SOCKET_ERROR);
        }

        $timeout = number_format($timeout, 3);
        $seconds = floor($timeout);
        $microseconds = (($timeout - $seconds) * 1000);
        stream_set_timeout($fp, $seconds, $microseconds);

        return $fp;
    }

    /**
     * Indicates whether $response is an error response
     * @param string $response The Nats Server response.
     * @return boolean
     */
    private function isErrorResponse($response)
    {
        return substr($response, 0, 4) === '-ERR';
    }

    /**
     * Process information returned by the server after connection.
     *
     * @param string $connectionResponse INFO message.
     *
     * @return void
     */
    private function processServerInfo($connectionResponse)
    {
        $this->serverInfo = new ServerInfo($connectionResponse);
    }

    /**
     * Create uuid
     * @return string
     */
    private function uuid()
    {
        return md5(time() . mt_rand(1, 1000000) . uniqid());
    }

}