<?php
declare(strict_types=1);

namespace unit;

use Codeception\Test\Unit;
use Predis\Client;

/**
 * Test redis component cache
 */
class CacheTest extends Unit
{
    public Client $clientSingleInstance;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->clientSingleInstance = new Client(
            ['host' => 'redis-single', 'port' => 6380, 'password' => 'Password']
        );
    }

    /**
     * Test connect to redis single used to Predis
     * @return void
     */
    public function testClientVendorConnectToSingleRedis(): void
    {
        $this->clientSingleInstance->set('testSingleKey', 'testSingleValue');

        $this->assertTrue($this->clientSingleInstance->isConnected());
    }

    /**
     * Test set and get value to redis single used to Predis
     * @return void
     */
    public function testClientVendorSetAndGetValueSingleRedis(): void
    {
        $this->clientSingleInstance->set('testSingleKey1', 'testSingleValue1');

        $this->assertEquals('testSingleValue1', $this->clientSingleInstance->get('testSingleKey1'));
    }
}
