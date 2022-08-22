<?php
declare(strict_types=1);

namespace unit;

use Codeception\Test\Unit;
use Predis\Client;

/**
 * Test redis component cache
 */
class RedisSingleCacheTest extends Unit
{
    public Client $clientInstance;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->clientInstance = new Client(
            ['host' => 'redis-single', 'port' => 6380, 'password' => 'Password']
        );
    }

    /**
     * Test connect to redis single used to Predis
     * @return void
     */
    public function testClientVendorConnect(): void
    {
        $this->clientInstance->set('testKey', 'testValue');

        $this->assertTrue($this->clientInstance->isConnected());
    }

    /**
     * Test set and get value to redis single used to Predis
     * @return void
     */
    public function testClientVendorSetAndGetValue(): void
    {
        $this->clientInstance->set('testKey1', 'testValue1');

        $this->assertEquals('testValue1', $this->clientInstance->get('testKey1'));
    }
}
