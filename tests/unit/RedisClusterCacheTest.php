<?php
declare(strict_types=1);

namespace unit;

use Codeception\Test\Unit;
use Predis\Client;

/**
 * Test redis component cache for cluster instance
 */
class RedisClusterCacheTest extends Unit
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
                ['host' => 'redis1', 'port' => 6381,],
                ['host' => 'redis2', 'port' => 6382,],
                ['host' => 'redis3', 'port' => 6383,],
                ['host' => 'redis4', 'port' => 6384,],
                ['host' => 'redis5', 'port' => 6385,],
                ['host' => 'redis6', 'port' => 6386,],
            ],
            [
                'cluster' => 'redis',
                'parameters' => [
                    'password' => 'Password',
                ],
            ],
        );
    }

    /**
     * Test connect to redis cluster used to Predis
     * @return void
     */
    public function testClientVendorConnect(): void
    {
        $this->clientInstance->set('testKey', 'testValue');

        $this->assertTrue($this->clientInstance->isConnected());
    }

    /**
     * Test set and get value to redis cluster used to Predis
     * @return void
     */
    public function testClientVendorSetAndGetValue(): void
    {
        $this->clientInstance->set('testKey1', 'testValue1');

        $this->assertEquals('testValue1', $this->clientInstance->get('testKey1'));
    }

}
