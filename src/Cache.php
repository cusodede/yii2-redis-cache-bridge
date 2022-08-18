<?php
declare(strict_types=1);

namespace Cusodede\Redis;

use Predis\Client;
use Predis\ClientInterface;
use Predis\Command\ServerFlushDatabase;
use Predis\Connection\StreamConnection;
use yii\caching\Cache as Yii2Cache;
use Yiisoft\Cache\Redis\RedisCache;

class Cache extends Yii2Cache
{
    public array $clientParams = [];
    public array $clientOptions = [];
    public bool $isCluster = true;

    private RedisCache $instance;
    private ClientInterface $client;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->client = $this->getClient();
        $this->instance = $this->getInstance();
    }

    /**
     * @inheritDoc
     */
    public function getValue($key)
    {
        $data = $this->instance->get($key);
        return serialize($data);
    }

    /**
     * @inheritDoc
     */
    public function getValues($keys)
    {
        if ($this->isCluster) {
            return parent::getValues($keys);
        }

        $data = $this->instance->getMultiple($keys);
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = serialize($value);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function setValue($key, $value, $duration)
    {
        return $this->instance->set($key, $value, $duration);
    }

    /**
     * @inheritDoc
     */
    public function deleteValue($key)
    {
        return $this->instance->delete($key);
    }

    /**
     * @inheritDoc
     */
    public function flushValues()
    {
        if ($this->isCluster) {
            $flushDbCommand = new ServerFlushDatabase();
            foreach ($this->client->getConnection() as $node) {
                /** @var StreamConnection $node */
                $node->executeCommand($flushDbCommand);
            }
            return true;
        }

        return $this->client->flushall();
    }

    /**
     * @inheritDoc
     */
    public function addValue($key, $value, $duration)
    {
        return $this->instance->set($key, $value, $duration);
    }

    /**
     * @return ClientInterface
     */
    private function getClient(): ClientInterface
    {
        return new Client($this->clientParams, $this->clientOptions);
    }

    /**
     * @return RedisCache
     */
    private function getInstance(): RedisCache
    {
        return new RedisCache($this->client);
    }
}
