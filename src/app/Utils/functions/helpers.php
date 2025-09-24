<?php

use Dotenv\Dotenv;

function env(string $key, $default = null) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../');
    $dotenv->load();
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false) {
        return $default;
    }
    return $value;
}