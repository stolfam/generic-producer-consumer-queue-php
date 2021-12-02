<?php

    require_once __DIR__ ."/../src/bootstrap.php";
    require_once __DIR__."/implementations/SimpleMessage.php";
    require_once __DIR__."/implementations/SimpleConsumer.php";
    require_once __DIR__."/implementations/JsonMessage.php";
    require_once __DIR__."/implementations/JsonConsumer.php";

    $manager = new \Stolfam\GPCQ\Manager(new \Stolfam\GPCQ\Storages\FileStorage(__DIR__ . "/temp"));

    $manager->addConsumer(new \Stolfam\GPCQ\Test\SimpleConsumer());
    $manager->addConsumer(new \Stolfam\GPCQ\Test\JsonConsumer());

    while (true) {
        $manager->run(50);
        echo "Waiting for next messages ...\n";
        sleep(1);
    }
