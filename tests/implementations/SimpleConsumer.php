<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ\Test;

    use Stolfam\GPCQ\Message;


    /**
     * Class Consumer
     * @package Stolfam\GPCQ\Test
     */
    class SimpleConsumer implements \Stolfam\GPCQ\Consumer
    {
        public function processMessage(Message $message): bool
        {
            if ($message instanceof SimpleMessage) {
                //$ts = strtotime($message->ts);
                echo "Message #" . $message->id . " received at " . $message->ts .
                    " processed at time " . date("H:i:s") . "\n";

                return true;
            }

            return false;
        }

        public static function getProcessableType(): string
        {
            return SimpleMessage::class;
        }
    }