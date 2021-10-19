<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ\Test;

    use Stolfam\GPCQ\Message;


    /**
     * Class UnprocessableMessage
     * @package Stolfam\GPCQ\Test
     */
    final class UnprocessableMessage extends Message
    {
        public bool $flag = false;

        /**
         * UnprocessableMessage constructor.
         * @param bool $flag
         */
        public function __construct(bool $flag)
        {
            $this->flag = $flag;
        }

        public static function createFromJson(string $json): Message
        {
            $o = json_decode($json);

            return new UnprocessableMessage($o->flag);
        }

        public function toJson(): string
        {
            return json_encode(["flag" => $this->flag]);
        }
    }