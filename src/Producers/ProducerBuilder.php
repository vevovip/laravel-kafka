<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka\Producers;

use Junges\Kafka\Config\Config;
use Junges\Kafka\Config\Sasl;
use Junges\Kafka\Contracts\MessageSerializer;
use Junges\Kafka\Exceptions\CouldNotPublishMessage;

final class ProducerBuilder extends \Junges\Kafka\Producers\ProducerBuilder implements CanProduceMessages
{
    private array $options = [];
    private MessageSerializer $serializer;
    private ?Sasl $saslConfig = null;
    private string $topic;
    private string $broker;

    public function __construct(string $topic, ?string $broker = null)
    {
        parent::__construct($topic, $broker);
        $this->topic = $topic;
        $this->serializer = app(MessageSerializer::class);
        $this->broker = $broker ?? config('kafka.brokers');
    }

    /**
     * @throws CouldNotPublishMessage
     */
    public function batchSend(MessageBatch $messageBatch): bool
    {
        $producer = $this->build();

        return $producer->batchProduce($messageBatch);
    }

    protected function build(): Producer
    {
        $conf = new Config(
            broker: $this->broker,
            topics: [$this->getTopic()],
            securityProtocol: $this->saslConfig?->getSecurityProtocol(),
            sasl: $this->saslConfig,
            customOptions: $this->options,
        );

        return app(Producer::class, [
            'config' => $conf,
            'topic' => $this->topic,
            'serializer' => $this->serializer,
        ]);
    }
}
