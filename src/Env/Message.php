<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ;

    /**
     * Class Message
     * @package Stolfam\GPCQ
     */
    abstract class Message
    {
        public abstract static function createFromJson(string $json): Message;

        public abstract function toJson(): string;

        public static final function isValid(Message $message): bool
        {
            return $message->toJson() === $message::createFromJson($message->toJson())
                    ->toJson();
        }
    }