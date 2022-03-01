# Larvel Kafka Wrapper for mateusjunges/laravel-kafka

- This package adds support of batch producing
- This package adds support of batch processing messages

## Installation:
- ``composer require chocofamilyme/laravel-kafka``
- php artisan vendor:publish --tag=chocofamily-laravel-kafka-config

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
