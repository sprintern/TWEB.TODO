<?php

declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_NAME = 'tweb_todo_lab5';
const DB_USER = 'root';
const DB_PASS = '';

function getConnection(): PDO
{
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    return new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
