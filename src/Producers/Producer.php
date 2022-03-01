<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka\Producers;

use Junges\Kafka\Config\Config;
use Junges\Kafka\Contracts\MessageSerializer;
use Junges\Kafka\Exceptions\CouldNotPublishMessage;
use RdKafka\Producer as KafkaProducer;
use SplDoublyLinkedList;

final class Producer extends \Junges\Kafka\Producers\Producer
{
    private KafkaProducer $producer;

    public function __construct(
        private Config $config,
        private string $topic,
        private MessageSerializer $serializer
    ) {
        parent::__construct($this->config, $this->topic, $this->serializer);
        $this->producer = app(KafkaProducer::class, [
            'conf' => $this->setConf($this->config->getProducerOptions()),
        ]);
    }

    public function batchProduce(MessageBatch $messageBatch)
    {
        $topic = $this->producer->newTopic($this->topic);

        $messagesIterator = $messageBatch->getMessages();

        $messagesIterator->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);

        foreach ($messagesIterator as $message) {
            $message = $this->serializer->serialize($message);

            if (method_exists($topic, 'producev')) {
                $topic->producev(
                    partition: $message->getPartition(),
                    msgflags: RD_KAFKA_MSG_F_BLOCK,
                    payload: $message->getBody(),
                    key: $message->getKey(),
                    headers: $message->getHeaders()
                );
            } else {
                $topic->produce(
                    partition: $message->getPartition(),
                    msgflags: 0,
                    payload: $message->getBody(),
                    key: $message->getKey()
                );
            }

            $this->producer->poll(0);
        }

        return retry(10, function () {
            $result = $this->producer->flush(1000);

            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                return true;
            }

            throw CouldNotPublishMessage::flushError();
        });
    }
}
