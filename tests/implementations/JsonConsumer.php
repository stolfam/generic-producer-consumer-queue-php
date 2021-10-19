<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ\Test;

    use Stolfam\GPCQ\Consumer;
    use Stolfam\GPCQ\Message;


    /**
     * Class JsonConsumer
     * @package Stolfam\GPCQ\Test
     */
    final class JsonConsumer implements Consumer
    {
        public static function getProcessableType(): string
        {
            return JsonMessage::class;
        }

        public function processMessage(Message $message): bool
        {
            if ($message instanceof JsonMessage) {
                echo "JSON data received: $message->json \n";

                return true;
            }

            return false;
        }

        public function listErrors(): array
        {
            return [];
        }
    }