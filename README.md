# yii2-redis-cache-bridge

## How to Redis cluster connection

```php
$connect = [
    ...
    'clientParams' => ['host' => 'cluster1:6379', 'host' => 'cluster2:6379', 'host' => 'cluster3:6379', ...],
    'clientOptions' => [
        'cluster' => 'redis',
        'parameters' => [
            'password' => 'password',
        ],
    ],
];
```