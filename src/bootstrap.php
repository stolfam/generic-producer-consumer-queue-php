<?php
    $files = array_merge(glob(__DIR__ . "/*.php"), glob(__DIR__ . "/Env/*.php"), glob(__DIR__ . "/Storages/*.php"));

    foreach ($files as $file) {
        require_once $file;
    }