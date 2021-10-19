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
            $this->consumers[spl_object_hash($consumer)] = $consumer;
        }

        /**
         * @param int $rounds
         */
        public function run(int $rounds = 1): void
        {
            for ($i = 0; $i < $rounds; $i++) {
                foreach ($this->consumers as $consumer) {
                    $message = $this->storage->nextMessage();
                    if ($message !== null) {
                        if ($consumer->processMessage($message)) {
                            $this->storage->dropCurrentMessage();
                        } else {
                            $this->storage->decreasePriorityOfCurrentMessage();
                            foreach ($consumer->listErrors() as $error) {
                                $this->errors[] = $error;
                            }
                        }
                    }
                }
            }
        }
    }