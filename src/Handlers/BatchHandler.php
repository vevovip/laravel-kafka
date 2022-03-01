<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka\Handlers;

use Chocofamilyme\LaravelKafka\BatchRepositories\BatchRepositoryInterface;
use Closure;
use Junges\Kafka\Contracts\KafkaConsumerMessage;

class BatchHandler
{
    protected BatchRepositoryInterface $repository;
    protected Closure $callback;

    public function __construct(protected int $batchSizeLimit, callable $callback)
    {
        $this->repository = app(BatchRepositoryInterface::class);
        $this->callback = Closure::fromCallable($callback);
        $this->repository->reset();
    }

    public function __invoke(KafkaConsumerMessage $message)
    {
        $this->repository->push($message);

        if ($this->repository->getBatchSize() < $this->batchSizeLimit) {
            return;
        }

        ($this->callback)($this->repository->getBatch());

        $this->repository->reset();
    }
}
