<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka;

use Chocofamilyme\LaravelKafka\Producers\CanProduceMessages;
use Chocofamilyme\LaravelKafka\Producers\ProducerBuilder;

final class Kafka extends \Junges\Kafka\Kafka
{
    /**
     * Creates a new ProducerBuilder instance, setting brokers and topic.
     *
     * @param string|null $broker
     * @param string $topic
     * @return CanProduceMessages
     */
    public function publishOn(string $topic, string $broker = null): CanProduceMessages
    {
        return new ProducerBuilder(
            topic: $topic,
            broker: $broker ?? config('kafka.brokers')
        );
    }
}
