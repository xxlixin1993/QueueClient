<?php
/**
 * Response.php
 * Sub receive data config
 * User: lixin
 * Date: 17-12-25
 */

namespace LQueue\nats;


class Response
{
    /**
     * Message Subject
     * @var string
     */
    private $subject;

    /**
     * Message Body
     * @var string
     */
    public $body;

    /**
     * Message Sid
     * @var string
     */
    private $sid;

    /**
     * Message related connection
     * @var NatsQueue
     */
    private $conn;

    /**
     * Message constructor.
     *
     * @param string     $subject Message subject.
     * @param string     $body    Message body.
     * @param string     $sid     Message Sid.
     * @param NatsQueue $conn    Message Connection.
     */
    public function __construct($subject, $body, $sid, NatsQueue $conn)
    {
        $this->setSubject($subject);
        $this->setBody($body);
        $this->setSid($sid);
        $this->setConn($conn);
    }

    /**
     * Set subject.
     * @param string $subject Subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get subject
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     * @param string $body Body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get body
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set Sid
     * @param string $sid Sid
     * @return $this
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
        return $this;
    }

    /**
     * Get Sid
     * @return string
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * String representation of a message
     * @return string
     */
    public function __toString()
    {
        return $this->getBody();
    }

    /**
     * Set Connection
     * @param NatsQueue $conn Connection
     * @return $this
     */
    public function setConn(NatsQueue $conn)
    {
        $this->conn = $conn;
        return $this;
    }

    /**
     * Get Connection
     * @return NatsQueue
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * Allows you reply the message with a specific body
     * @param string $body Body to be set.
     * @return void
     */
    public function reply($body)
    {
        $this->conn->publish(
            $this->subject,
            $body
        );
    }
}