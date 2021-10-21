<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ\Test;

    use Stolfam\GPCQ\Manager;
    use Stolfam\GPCQ\Message;


    /**
     * Class LinkedConsumer
     * @package Stolfam\GPCQ\Test
     */
    final class LinkedConsumer implements \Stolfam\GPCQ\LinkedConsumer
    {
        private Manager $manager;

        public function processMessage(Message $message): bool
        {
            if ($message instanceof JsonMessage) {
                echo "JSON data received: $message->json \n";
                $o = json_decode($message->json);
                if (!isset($o->callback)) {
                    $this->manager->putMessage(new JsonMessage(json_encode([
                        "callback" => "reaction to JsonMessage"
                    ])));
                }

                return true;
            }
            if ($message instanceof SimpleMessage) {
                echo "SimpleMessage received: " . $message->id . "\n";
                $this->manager->putMessage(new JsonMessage(json_encode([
                    "callback" => "reaction to SimpleMessage"
                ])));

                return true;
            }

            return false;
        }

        public function listErrors(): array
        {
            return [];
        }

        public function setManagerReference(Manager &$manager): void
        {
            $this->manager = $manager;
        }
    }