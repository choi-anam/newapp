<?php

$helperFiles = glob(__DIR__ . '/*.php');

foreach ($helperFiles as $file) {
    if (basename($file) !== 'helpers.php') {
        require_once $file;
    }
}