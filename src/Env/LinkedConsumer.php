<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ;

    /**
     * Interface IConsumer
     * @package Stolfam\GPCQ
     */
    interface LinkedConsumer extends Consumer
    {
        public function setManagerReference(Manager &$manager): void;
    }