<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ;

    /**
     * Interface Storage
     * @package Stolfam\GPCQ
     */
    interface Storage
    {
        public function addMessage(Message $message): bool;

        public function nextMessage(): ?Message;

        public function dropCurrentMessage(): bool;

        public function decreasePriorityOfCurrentMessage(): bool;
    }