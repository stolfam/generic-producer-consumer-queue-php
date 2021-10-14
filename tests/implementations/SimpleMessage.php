<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ\Test;

    use Stolfam\GPCQ\Message;


    /**
     * Class SimpleMessage
     * @package Stolfam\GPCQ\Test
     */
    final class SimpleMessage extends Message
    {
        public int $id;
        public int $ts;

        /**
         * SimpleMessage constructor.
         * @param int      $id
         * @param int|null $ts
         */
        public function __construct(int $id, ?int $ts = null)
        {
            $this->id = $id;
            $this->ts = $ts ?? time();
        }

        public static function createFromJson(string $json): Message
        {
            $o = json_decode($json);

            return new SimpleMessage($o->id, $o->ts);
        }

        public function toJson(): string
        {
            return json_encode([
                "id" => $this->id,
                "ts" => $this->ts
            ]);
        }
    }