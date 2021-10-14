<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ;

    /**
     * Class Message
     * @package Stolfam\GPCQ
     */
    abstract class Message
    {
        /**
         * Message constructor.
         */
        public function __construct()
        {
            if (!Message::isValid($this)) {
                throw new \InvalidArgumentException("Message is not valid.");
            }
        }

        public abstract static function createFromJson(string $json): Message;

        public abstract function toJson(): string;

        public static final function isValid(Message $message): bool
        {
            return $message->toJson() === Message::createFromJson($message->toJson())
                    ->toJson();
        }
    }