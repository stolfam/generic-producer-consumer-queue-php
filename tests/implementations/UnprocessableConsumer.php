<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ\Test;

    use Stolfam\GPCQ\Consumer;
    use Stolfam\GPCQ\Message;


    /**
     * Class UnprocessableConsumer
     * @package Stolfam\GPCQ\Test
     */
    final class UnprocessableConsumer implements Consumer
    {
        public function processMessage(Message $message): bool
        {
            if ($message instanceof UnprocessableMessage) {
                echo "Processing message with flag: " . ($message->flag ? "true" : "false") . "\n";
                if ($message->flag) {
                    echo "Process like a regular message.\n";
                } else {
                    echo "Decrease priority of this message.\n";
                }

                return $message->flag;
            }

            return false;
        }

        public function listErrors(): array
        {
            return [
                "Error message."
            ];
        }
    }