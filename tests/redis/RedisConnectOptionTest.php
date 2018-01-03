<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class RedisConnectOptionTest extends TestCase
{
    /**
     * Test default config
     */
    public function testDefaultSetting()
    {
        $options = new \LQueue\redis\ConnectOption();

        $this->assertEquals('localhost', $options->getHost());
        $this->assertEquals(6379, $options->getPort());
        $this->assertNull($options->getPass());
        $this->assertEquals(3, $options->getTimeout());
    }

    /**
     * Test set and get function
     */
    public function testSettingAndGetting()
    {
        $options = new \LQueue\redis\ConnectOption();
        $options->setHost('127.0.0.1')->setPort(4222)->setPass('password')->setTimeout(2);

        $this->assertEquals('127.0.0.1', $options->getHost());
        $this->assertEquals(4222, $options->getPort());
        $this->assertEquals('password', $options->getPass());
        $this->assertEquals(2, $options->getTimeout());
    }

    /**
     * Test create ConnectOption object
     */
    public function testCreateConnectOptionObj()
    {
        $options = [
            'host' => '1.1.1.1',
            'port' => '1234',
            'pass' => 'passwd',
            'timeout' => '5',
        ];
        $optionObj = new \LQueue\redis\ConnectOption($options);
        $this->assertEquals('1.1.1.1', $optionObj->getHost());
        $this->assertEquals(1234, $optionObj->getPort());
        $this->assertEquals('passwd', $optionObj->getPass());
        $this->assertEquals(5, $optionObj->getTimeout());
    }

    /**
     * Test setConnectionOptions function
     */
    public function testSetConnectionOptions()
    {
        $options = [
            'host' => '1.1.1.1',
            'port' => '1234',
            'pass' => 'passwd',
            'timeout' => '5',
        ];
        $optionObj = new \LQueue\redis\ConnectOption();
        $optionObj->setConnectionOptions($options);
        $this->assertEquals('1.1.1.1', $optionObj->getHost());
        $this->assertEquals(1234, $optionObj->getPort());
        $this->assertEquals('passwd', $optionObj->getPass());
        $this->assertEquals(5, $optionObj->getTimeout());
    }

    /**
     * Test __toString function
     */
    public function testToString()
    {
        $options = [
            'host' => '1.1.1.1',
            'port' => 1234,
            'pass' => 'passwd',
            'timeout' => 5,
        ];
        $optionObj = new \LQueue\redis\ConnectOption();
        $optionObj->setConnectionOptions($options);

        $expectedString = json_encode([
            'host' => '1.1.1.1',
            'port' => 1234,
            'timeout' => 5,
            'pass' => 'passwd',
        ]);
        $this->assertEquals($expectedString, $optionObj->__toString());
    }
}