# Yii2 redis cache bridge

The bridge between Yii2 cache and Yii3 cache for Redis. Implements support for working with cluster variants and a single Redis installation

[![Build Status](https://github.com/cusodede/yii2-redis-cache-bridge/actions/workflows/tests.yml/badge.svg)](https://github.com/cusodede/yii2-redis-cache-bridge/actions)

## Requirements

- PHP 8.0 or higher.

## How to Redis connection

```php
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
```

## Tests via Docker

### Prepare

```bash
# {{ v }} = 8.0, 8.1, 8.2. Default PHP 8.1
make build v=8.1
```

### Unit testing

```bash
# {{ v }} = 8.0, 8.1, 8.2. Default PHP 8.1
make test v=8.1
```

### Static analysis

```bash
# {{ v }} = 8.0, 8.1, 8.2. Default PHP 8.1
make static-analyze v=8.1
```