<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka\Producers;

interface CanProduceMessages extends \Junges\Kafka\Contracts\CanProduceMessages
{
    public function batchSend(MessageBatch $messageBatch): bool;
}
