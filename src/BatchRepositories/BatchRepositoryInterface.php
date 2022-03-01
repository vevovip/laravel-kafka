<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka\BatchRepositories;

use Illuminate\Support\Collection;
use Junges\Kafka\Contracts\KafkaConsumerMessage;

interface BatchRepositoryInterface
{
    public function push(KafkaConsumerMessage $message): void;

    public function getBatch(): Collection;

    public function getBatchSize(): int;

    public function reset(): void;
}
