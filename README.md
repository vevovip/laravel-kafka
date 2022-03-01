# Laravel Kafka Wrapper for mateusjunges/laravel-kafka

- This package adds support of batch producing
- This package adds support of batch processing messages

## Requirements:
- php ^8.0
- Laravel ^8.0
- <a href="https://github.com/edenhill/librdkafka">edenhill/librdkafka</a>
- <a href="https://arnaud.le-blanc.net/php-rdkafka-doc/phpdoc/rdkafka.installation.html">arnaud-lb/php-rdkafka</a>

## Installation:
- `composer require chocofamilyme/laravel-kafka`
- `php artisan vendor:publish --tag=laravel-kafka`

## If you want to batch messages in different storage then:
- implement ``Chocofamilyme\LaravelKafka\BatchRepositories\BatchRepositoryInterface``
- Override default repository

```php
<?php

return [
    // ... other properties

    /*
     | Repository for batching messages together
     | Implement BatchRepositoryInterface to save batches in different storage
     */
    'batch_repository' => env('KAFKA_BATCH_REPOSITORY', \Chocofamilyme\LaravelKafka\BatchRepositories\InMemoryBatchRepository::class),
];
```
