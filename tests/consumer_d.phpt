<?php

    require_once __DIR__ . "/../src/Env/Message.php";
    require_once __DIR__ . "/../src/Env/Consumer.php";
    require_once __DIR__ . "/../src/Env/Storage.php";
    require_once __DIR__ . "/../src/Env/Manager.php";
    require_once __DIR__ . "/implementations/SimpleMessage.php";
    require_once __DIR__ . "/implementations/JsonMessage.php";
    require_once __DIR__ . "/../src/Storages/FileStorage.php";
    require_once __DIR__ . "/implementations/SimpleConsumer.php";
    require_once __DIR__ . "/implementations/JsonConsumer.php";

    $manager = new \Stolfam\GPCQ\Manager(new \Stolfam\GPCQ\Storages\FileStorage(__DIR__ . "/temp"));

    $manager->addConsumer(new \Stolfam\GPCQ\Test\SimpleConsumer());
    $manager->addConsumer(new \Stolfam\GPCQ\Test\JsonConsumer());

    while (true) {
        $manager->run(5);
        echo "Waiting for next messages ...\n";
        sleep(1);
    }
