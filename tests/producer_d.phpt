<?php

    require_once __DIR__ ."/../src/bootstrap.php";require_once __DIR__ ."/../src/bootstrap.php";
    require_once __DIR__."/implementations/SimpleMessage.php";
    require_once __DIR__."/implementations/SimpleConsumer.php";
    require_once __DIR__."/implementations/JsonMessage.php";
    require_once __DIR__."/implementations/JsonConsumer.php";

    $manager = new \Stolfam\GPCQ\Manager(new \Stolfam\GPCQ\Storages\FileStorage(__DIR__ . "/temp"));

    while (true) {
        switch (rand(0, 1)) {
            case 0:
                $id = rand(1, 999);
                $manager->putMessage(new \Stolfam\GPCQ\Test\SimpleMessage($id));
                echo "Message ID sent: $id\n";
                break;
            case 1:
                $data = "xyz" . rand(10, 99);
                $manager->putMessage(new \Stolfam\GPCQ\Test\JsonMessage(json_encode([
                    "data" => $data
                ])));
                echo "Data sent: $data\n";
                break;
            default:
                break;
        }
        sleep(rand(0, 1));
    }
