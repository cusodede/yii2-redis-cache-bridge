<?php

declare(strict_types=1);

namespace unit;

use Predis\Client;
use Codeception\Test\Unit;

class RedisSentinelTest extends Unit
{
    public Client $clientInstance;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->clientInstance = new Client(
            [
                ['host' => 'sentinel1', 'port' => 26379,],
                ['host' => 'sentinel2', 'port' => 26380,],
                ['host' => 'sentinel3', 'port' => 26381,],
            ],
            [
                'replication' => 'sentinel',
                'service' => 'master01'
            ],
        );
    }

    public function testClientVendorConnect(): void
    {
        $this->clientInstance->set('testKey', 'testValue');

        $this->assertTrue($this->clientInstance->isConnected());
    }

    public function testClientVendorSetAndGetValue(): void
    {
        $this->clientInstance->set('testKey1', 'testValue1');

        $this->assertEquals('testValue1', $this->clientInstance->get('testKey1'));
    }
}
