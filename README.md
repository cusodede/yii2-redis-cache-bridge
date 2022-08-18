# yii2-redis-cache-bridge

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