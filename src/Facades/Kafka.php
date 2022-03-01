<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka\Facades;

use Chocofamilyme\LaravelKafka\Support\Testing\KafkaFake;
use Illuminate\Support\Facades\Facade;

class Kafka extends Facade
{
    /**
     * Replace the bound instance with a fake.
     *
     * @return KafkaFake
     */
    public static function fake(): KafkaFake
    {
        static::swap($fake = new KafkaFake());

        return $fake;
    }

    public static function getFacadeAccessor(): string
    {
        return \Chocofamilyme\LaravelKafka\Kafka::class;
    }
}
