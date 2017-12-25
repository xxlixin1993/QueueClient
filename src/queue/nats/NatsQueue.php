<?php

/**
 * NatsQueue.php
 * Manage Nats Queue.
 * User: lixin
 * Date: 17-12-22
 */
namespace queue\nats;

use queue\interfaces\IQueue;

class NatsQueue implements IQueue
{
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
     * @var mixed
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
     * @throws \Exception
     * @author lixin
     */
    public function driver()
    {
        $timeout = $this->options->getTimeout();
        $this->socket = $this->getSocket($this->options->getAddress(), $timeout);


        $msg = 'CONNECT ' . $this->options;
        $this->send($msg);
        $connectResponse = $this->receive();

        if ($this->isErrorResponse($connectResponse) === true) {
            throw new \Exception('Connect error, Msg: ' . $connectResponse);
        } else {
            $this->processServerInfo($connectResponse);
        }

        $this->ping();
        $pingResponse = $this->receive();

        if ($this->isErrorResponse($pingResponse) === true) {
            throw new \Exception('Ping error, Msg: ' . $pingResponse);
        }
    }

    /**
     * Send message
     * @param string $subject Commend
     * @param string $data Send data
     * @param int $id Client id
     * @return mixed
     * @author lixin
     */
    public function publish($subject, $data, $id)
    {
        $msg = 'PUB ' . $subject;
        if ($id !== null) {
            $msg = $msg . ' ' . $id;
        }

        $msg = $msg . ' ' . strlen($data);
        $this->send($msg . "\r\n" . $data);
        $this->pubs += 1;
    }

    /**
     * Sends PING message.
     * @return void
     */
    public function ping()
    {
        $msg = 'PING';
        $this->send($msg);
        $this->pings += 1;
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
                throw new \Exception('Sending data error');
            }

            if ($written === 0) {
                throw new \Exception('Can not input anything in socket');
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
            throw new \Exception('Create socket error: ' . $errString, $errNo);
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


}