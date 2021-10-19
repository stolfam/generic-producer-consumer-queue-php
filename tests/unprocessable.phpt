<?php

    require_once __DIR__ . "/../src/bootstrap.php";
    require_once __DIR__ . "/implementations/UnprocessableMessage.php";
    require_once __DIR__ . "/implementations/UnprocessableConsumer.php";

    function getFilename(): string
    {
        $files = scandir(__DIR__ . "/temp");
        foreach ($files as $file) {
            if ($file == "." || $file == ".." || $file == ".gitignore") {
                continue;
            }

            return $file;
        }
    }

    $manager = new \Stolfam\GPCQ\Manager(new \Stolfam\GPCQ\Storages\FileStorage(__DIR__ . "/temp"));

    $manager->addConsumer(new \Stolfam\GPCQ\Test\UnprocessableConsumer());

    $manager->putMessage(new \Stolfam\GPCQ\Test\UnprocessableMessage(false));
    echo "Message added.\n";

    $files = scandir(__DIR__ . "/temp");
    echo getFilename() . "\n";

    $manager->run();

    echo getFilename() . "\n";
    unlink(__DIR__ . "/temp/" . getFilename());