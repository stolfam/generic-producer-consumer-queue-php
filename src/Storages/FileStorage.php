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
        private ?string $currentFile = null;

        public array $ignorePatterns = [
            "^\.gitignore$"
        ];

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
            if (!Message::isValid($message)) {
                return false;
            }

            $fullMessage = [
                "type" => $message::class,
                "json" => $message->toJson()
            ];

            file_put_contents($this->path . "/" . $this->getSafeFilename($message), json_encode($fullMessage));

            return true;
        }

        private function getSafeFilename(Message $message): string
        {
            return microtime(true) . "_" . str_replace(["/", "\\"], "-", $message::class) . "_" .
                md5(openssl_random_pseudo_bytes(16)) . ".json";
        }

        public function nextMessage(): ?Message
        {
            $files = scandir($this->path);
            foreach ($files as $file) {
                if ($file == "." || $file == "..") {
                    continue;
                }

                $ignore = false;
                foreach ($this->ignorePatterns as $ignorePattern) {
                    if (preg_match("~$ignorePattern~i", $file)) {
                        $ignore = true;
                        continue;
                    }
                }

                if ($ignore) {
                    continue;
                }

                $this->currentMessage = $this->path . "/" . $file;
                $this->currentFile = $file;

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

        public function decreasePriorityOfCurrentMessage(): bool
        {
            $fragments = explode("_", $this->currentFile);
            $newFilename = microtime(true);
            for ($i = 1; $i < count($fragments); $i++) {
                $newFilename .= "_" . $fragments[$i];
            }

            return rename($this->currentMessage, $this->path . "/" . $newFilename);
        }
    }