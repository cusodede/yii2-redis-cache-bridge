<?php

declare(strict_types=1);

namespace Cusodede\Redis;

use Predis\Client;
use Predis\ClientInterface;
use Predis\NotSupportedException;
use yii\caching\Cache as Yii2Cache;
use Yiisoft\Cache\Redis\RedisCache;

/**
 * @property-read bool $isCluster
 */
class Cache extends Yii2Cache
{
    public array $clientParams = [];
    public array $clientOptions = [];

    private RedisCache $instance;
    private ClientInterface $client;
    /**
     * @var bool if hashtags were supplied for a MGET/MSET operation
     */
    private bool $hashTagAvailable = false;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->setClient();
        $this->setInstance();
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
    public function getValues($keys): array
    {
        if ($this->isCluster && !$this->hashTagAvailable) {
            return parent::getValues($keys);
        }

        $data = $this->instance->getMultiple($keys);
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = serialize($value);
        }
        $this->hashTagAvailable = false;

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function setValue($key, $value, $duration): bool
    {
        return $this->addValue($key, $value, $duration);
    }

    /**
     * @inheritDoc
     */
    public function deleteValue($key): bool
    {
        return $this->instance->delete($key);
    }

    /**
     * @inheritDoc
     */
    public function flushValues(): bool
    {
        if ($this->isCluster) {
            return $this->instance->clear();
        }

        return $this->client->flushall();
    }

    /**
     * @inheritDoc
     */
    public function addValue($key, $value, $duration): bool
    {
        $ttl = $duration;
        if (0 !== $duration) {
            $ttl = $duration * 1000;
        }

        return $this->instance->set($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function exists($key): bool
    {
        return $this->instance->has($key);
    }

    /**
     * @inheritDoc
     */
    public function buildKey($key): string
    {
        if (is_string($key) && preg_match('/^(.*)({.+})(.*)$/', $key, $matches) === 1) {
            $this->hashTagAvailable = true;

            return parent::buildKey($matches[1] . $matches[3]) . $matches[2];
        }

        return parent::buildKey($key);
    }

    /**
     * @inheritDoc
     */
    public function flush(): bool
    {
        return $this->instance->clear();
    }

    /**
     * @return bool
     */
    public function getIsCluster(): bool
    {
        try {
            $this->client->executeRaw(['CLUSTER INFO']);

            return true;
        } catch (NotSupportedException $exception) {
            return false;
        }
    }

    /**
     * @return void
     */
    private function setClient(): void
    {
        $this->client = new Client($this->clientParams, $this->clientOptions);
    }

    /**
     * @return void
     */
    private function setInstance(): void
    {
        $this->instance = new RedisCache($this->client);
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @return RedisCache
     */
    public function getInstance(): RedisCache
    {
        return $this->instance;
    }
}
