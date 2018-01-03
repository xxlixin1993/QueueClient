<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class NatsConnectOptionTest extends TestCase
{
    /**
     * Test default config
     */
    public function testDefaultSetting()
    {
        $options = new \LQueue\nats\ConnectOption();

        $this->assertEquals('localhost', $options->getHost());
        $this->assertEquals(4222, $options->getPort());
        $this->assertNull($options->getUser());
        $this->assertNull($options->getPass());
        $this->assertNull($options->getToken());
        $this->assertEquals('php', $options->getLang());
        $this->assertEquals(3, $options->getTimeout());
    }

    /**
     * Test set and get function
     */
    public function testSettingAndGetting()
    {
        $options = new \LQueue\nats\ConnectOption();
        $options->setHost('127.0.0.1')->setPort(4222)->setUser('user')
            ->setPass('password')->setTimeout(2)->setToken('testToken')
            ->setLang('php7');

        $this->assertEquals('127.0.0.1', $options->getHost());
        $this->assertEquals(4222, $options->getPort());
        $this->assertEquals('user', $options->getUser());
        $this->assertEquals('password', $options->getPass());
        $this->assertEquals('testToken', $options->getToken());
        $this->assertEquals('php7', $options->getLang());
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
            'user' => 'lixin',
            'pass' => 'passwd',
            'token' => 'testToken',
            'lang' => 'go',
            'timeout' => '5',
        ];
        $optionObj = new \LQueue\nats\ConnectOption($options);
        $this->assertEquals('1.1.1.1', $optionObj->getHost());
        $this->assertEquals(1234, $optionObj->getPort());
        $this->assertEquals('lixin', $optionObj->getUser());
        $this->assertEquals('passwd', $optionObj->getPass());
        $this->assertEquals('testToken', $optionObj->getToken());
        $this->assertEquals('go', $optionObj->getLang());
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
            'user' => 'lixin',
            'pass' => 'passwd',
            'token' => 'testToken',
            'lang' => 'go',
            'timeout' => '5',
        ];
        $optionObj = new \LQueue\nats\ConnectOption();
        $optionObj->setConnectionOptions($options);
        $this->assertEquals('1.1.1.1', $optionObj->getHost());
        $this->assertEquals(1234, $optionObj->getPort());
        $this->assertEquals('lixin', $optionObj->getUser());
        $this->assertEquals('passwd', $optionObj->getPass());
        $this->assertEquals('testToken', $optionObj->getToken());
        $this->assertEquals('go', $optionObj->getLang());
        $this->assertEquals(5, $optionObj->getTimeout());
    }

    /**
     * Test __toString function
     */
    public function testToString()
    {
        $options = [
            'host' => '1.1.1.1',
            'port' => '1234',
            'user' => 'lixin',
            'pass' => 'passwd',
            'token' => 'testToken',
            'lang' => 'go',
            'timeout' => '5',
        ];
        $optionObj = new \LQueue\nats\ConnectOption();
        $optionObj->setConnectionOptions($options);
        
        $expectedString = json_encode([
            'lang' => 'go',
            'user'=>'lixin',
            'pass'=>'passwd',
        ]);
        $this->assertEquals($expectedString, $optionObj->__toString());
    }
}