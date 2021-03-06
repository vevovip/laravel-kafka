<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka\Producers;

use Junges\Kafka\Message\Message;
use SplDoublyLinkedList;

final class MessageBatch
{
    private SplDoublyLinkedList $messages;

    public function __construct()
    {
        $this->messages = new SplDoublyLinkedList();
    }

    public function push(Message $message)
    {
        $this->messages->push($message);
    }

    public function getMessages(): SplDoublyLinkedList
    {
        return $this->messages;
    }
}
