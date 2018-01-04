<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class ServerInfoTest extends TestCase
{
    /**
     * Test ServerInfo set and get function
     */
    public function testSettingAndGetting()
    {
        $responseArr = [
            'server_id' => 'br9BfQLTHeYwZOtBSfP6PI',
            'version' => '1.0.4',
            'go' => 'go1.8.3',
            'host' => '0.0.0.0',
            'port' => 4222,
            'auth_required' => false,
            'ssl_required' => false,
            'tls_required' => false,
            'tls_verify' => false,
            'max_payload' => 1048576,
        ];
        $connectResponse = 'INFO ' . json_encode($responseArr);
        
        $serverInfo = new \LQueue\nats\ServerInfo($connectResponse);
        
        $this->assertEquals('br9BfQLTHeYwZOtBSfP6PI', $serverInfo->getServerID());
        $this->assertEquals('0.0.0.0', $serverInfo->getHost());
        $this->assertEquals('1.0.4', $serverInfo->getVersion());
        $this->assertEquals('go1.8.3', $serverInfo->getGoVersion());
        $this->assertEquals(4222, $serverInfo->getPort());
        $this->assertEquals(false, $serverInfo->isAuthRequired());
        $this->assertEquals(false, $serverInfo->isSSLRequired());
        $this->assertEquals(false, $serverInfo->isTLSVerify());
        $this->assertEquals(false, $serverInfo->isTLSRequired());
        $this->assertEquals(1048576, $serverInfo->getMaxPayload());


    }

}