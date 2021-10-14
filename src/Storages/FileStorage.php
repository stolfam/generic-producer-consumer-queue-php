<?php
    declare(strict_types=1);

    namespace Stolfam\GPCQ\Storages;

    use Stolfam\GPCQ\Message;
    use Stolfam\GPCQ\Storage;


    /**
     * Class FileStorage
     * @package Stolfam\GPCQ\Storages
     */
    class FileStorage implements Storage
    {
        private string $path;

        private ?string $currentMessage = null;

        /**
         * FileStorage constructor.
         * @param string $path
         */
        public function __construct(string $path)
        {
            $this->path = $path;

            if (!file_exists($this->path)) {
                mkdir($this->path);
            }
        }

        public function addMessage(Message $message): bool
        {
            $fullMessage = [
                "type" => $message::class,
                "json" => $message->toJson()
            ];

            file_put_contents($this->path . "/" . $this->getSafeFilename($message), json_encode($fullMessage));

            return true;
        }

        private function getSafeFilename(Message $message): string
        {
            return time() . "_" . str_replace(["/", "\\"], "-", $message::class) . "_" .
                md5(openssl_random_pseudo_bytes(16)) . ".json";
        }

        public function nextMessage(): ?Message
        {
            $files = scandir($this->path);
            foreach ($files as $file) {
                if ($file == "." || $file == "..") {
                    continue;
                }

                $this->currentMessage = $this->path . "/" . $file;

                $fullMessage = json_decode(file_get_contents($this->path . "/" . $file));

                try {
                    return $fullMessage->type::createFromJson($fullMessage->json);
                } catch (\Throwable $t) {
                    return null;
                }
            }

            return null;
        }

        public function dropCurrentMessage(): bool
        {
            if (!empty($this->currentMessage)) {
                return unlink($this->currentMessage);
            }
        }
    }