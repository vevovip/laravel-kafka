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

# Docs:
- <a href="https://junges.dev/documentation/laravel-kafka/v1.6/1-introduction">mateusjunges/laravel-kafka</a>

# Produce batch of messages at once

```php
use \Chocofamilyme\LaravelKafka\Producers\MessageBatch;
use \Chocofamilyme\LaravelKafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

$messageBatch = new MessageBatch();
$messageBatch->push(new Message(
    body: ['message' => 'some-message'],
    key: 'some-key',
    headers: ['some-header' => 'some-header-value'],
));
// ... add more messages to batch

$producer = Kafka::publishOn('my.topic');

$producer->batchSend($messageBatch);
```

# Consume batch

- Batch will be deleted after processing
- You can use any callable type as a callback of BatchHandler

```php

use \Chocofamilyme\LaravelKafka\Handlers\BatchHandler;
use \Chocofamilyme\LaravelKafka\Facades\Kafka;
use Illuminate\Support\Collection;

class Handler {
    public function __invoke(Collection $batch)
    {
        // Process batch
    }
}

$batchHandler = new BatchHandler(
    batchSizeLimit: 1000,
    callback: new Handler() // callable $callback
);

$consumer = Kafka::createConsumer(['my.topic'], 'group-id', 'broker');

$consumer
    ->withHandler($batchHandler)
    ->build()
    ->consume();
```