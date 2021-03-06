<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ;

    /**
     * Class Manager
     * @package Stolfam\GPCQ
     */
    class Manager
    {
        /** @var Consumer[] */
        private array $consumers;

        private Storage $storage;

        /** @var string[] */
        public array $errors = [];

        private array $readMessageHashes = [];

        /**
         * ConsumerManager constructor.
         * @param Storage $storage
         */
        public function __construct(Storage $storage)
        {
            $this->storage = $storage;
        }

        /**
         * @param Message $message
         * @return bool
         */
        public function putMessage(Message $message): bool
        {
            return $this->storage->addMessage($message);
        }

        /**
         * @param Consumer $consumer
         */
        public function addConsumer(Consumer $consumer): void
        {
            if ($consumer instanceof LinkedConsumer) {
                $consumer->setManagerReference($this);
            }
            $this->consumers[spl_object_hash($consumer)] = $consumer;
        }

        /**
         * @param int $rounds
         */
        public function run(int $rounds = 1): void
        {
            for ($i = 0; $i < $rounds; $i++) {
                foreach ($this->consumers as $id => $consumer) {
                    $message = $this->storage->nextMessage();

                    if ($message !== null) {
                        if ($this->wasMessageRead($id, $message)) {
                            $this->storage->decreasePriorityOfCurrentMessage();
                            continue;
                        }

                        if ($consumer->processMessage($message)) {
                            $this->storage->dropCurrentMessage();
                        } else {
                            $this->storage->decreasePriorityOfCurrentMessage();
                            foreach ($consumer->listErrors() as $error) {
                                $this->errors[] = $error;
                            }
                        }

                        $this->notifyMessageRead($id, $message);
                    }
                }
            }
        }

        public function messagesCount(): int
        {
            return $this->storage->messagesCount();
        }

        private function notifyMessageRead(string $consumerId, Message $message): void
        {
            $this->readMessageHashes[$consumerId][] = md5($message->toJson());
        }

        private function wasMessageRead(string $consumerId, Message $message): bool
        {
            if (!isset($this->readMessageHashes[$consumerId])) {
                return false;
            }

            return in_array(md5($message->toJson()), $this->readMessageHashes[$consumerId]);
        }
    }