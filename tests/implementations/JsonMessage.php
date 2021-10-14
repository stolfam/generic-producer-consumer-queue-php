<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ\Test;

    use Stolfam\GPCQ\Message;


    /**
     * Class JsonMessage
     * @package Stolfam\GPCQ\Test
     */
    final class JsonMessage extends Message
    {
        public string $json;

        /**
         * JsonMessage constructor.
         * @param string $json
         */
        public function __construct(string $json)
        {
            $this->json = $json;
        }

        public static function createFromJson(string $json): Message
        {
            $o = json_decode($json);

            return new JsonMessage($o->json);
        }

        public function toJson(): string
        {
            return json_encode([
                "json" => $this->json
            ]);
        }
    }