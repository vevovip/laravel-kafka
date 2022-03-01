<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka\BatchRepositories;

use Illuminate\Support\Collection;
use Junges\Kafka\Contracts\KafkaConsumerMessage;

final class InMemoryBatchRepository implements BatchRepositoryInterface
{
    private Collection $batch;

    public function push(KafkaConsumerMessage $message): void
    {
        $this->batch->push($message);
    }

    public function getBatch(): Collection
    {
        return $this->batch;
    }

    public function getBatchSize(): int
    {
        return $this->batch->count();
    }

    public function reset(): void
    {
        $this->batch = collect([]);
    }
}
