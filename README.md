# generic-producer-consumer-queue-php
If you struggling with queueing your data streams like bulk imports or exports, you can use my simple solution. These are simple classes for queueing data streams.

## Requirements
- PHP 8

## Preparing
Prepare your messages extending `abstract class Message`. It prevents parsing errors with built-in function `isValid()` located in abstract constructor.

Implements `interface Consumer` and create your own consumer logic. 

## Using
See folder `/tests` for examples, or start with `class Manager` like this:
```
// creating a manager, with storage for messages
$manager = new Manager(new FileStorage(__DIR__ . "/temp"));
```
Add one or more consumers, which process your messages (data):
```
$manager->addConsumer(new \Stolfam\GPCQ\Test\SimpleConsumer());
$manager->addConsumer(new \Stolfam\GPCQ\Test\JsonConsumer());
```
In your consumer script, you start calling function `run()` and messages will be processed:
```
$manager->run();
```
In your producer script, you can start adding messages:
```
$manager->putMessage(new \Stolfam\GPCQ\Test\SimpleMessage($id));
$manager->putMessage(new \Stolfam\GPCQ\Test\JsonMessage(json_encode([
    "data" => $data
])));
```
## Notes
- You can run function `run()` of `class Manager` in your script as a daemon
- One `class Manager` can process different types of messages. It depends on how many consumers you add.
## Storages
You can implement your own storages for messages or you can use a default one `class FileStorage`.