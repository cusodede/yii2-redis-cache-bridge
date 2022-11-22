# Yii2 redis cache bridge

The bridge between Yii2 cache and Yii3 cache for Redis. Implements support for working with cluster variants and a single Redis installation

[![Build Status](https://github.com/cusodede/yii2-redis-cache-bridge/actions/workflows/tests.yml/badge.svg)](https://github.com/cusodede/yii2-redis-cache-bridge/actions)

## How to Redis cluster connection
https://redis.io/docs/manual/scaling/

```php
$connect = [
    ...
    'clientParams' => ['host' => 'cluster1:6379', 'host' => 'cluster2:6379', ...],
    'clientOptions' => [
        'cluster' => 'redis',
        'parameters' => [
            'password' => 'password',
        ],
    ],
];

//OR

return [
    'class' => Cache::class,
    'keyPrefix' => 'YOUR_APP_PREFIX_',
    'clientParams' => [
        'host' => getenv('REDIS_HOST'),
        'port' => getenv('REDIS_PORT'),
        'password' => getenv('REDIS_PASSWORD')
    ]
];
```