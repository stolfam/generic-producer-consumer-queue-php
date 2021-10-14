<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ;

    /**
     * INterface Consumer
     * @package Stolfam\GPCQ
     */
    interface Consumer
    {
        public function processMessage(Message $message): bool;
    }