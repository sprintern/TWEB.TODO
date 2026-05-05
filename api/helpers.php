<?php

declare(strict_types=1);

require_once __DIR__ . '/../db.php';

function sendJson(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function readJsonBody(): array
{
    $body = file_get_contents('php://input');
    $data = json_decode($body ?: '{}', true);

    return is_array($data) ? $data : [];
}

function handleApiError(Throwable $error): void
{
    sendJson([
        'success' => false,
        'message' => 'Server error: ' . $error->getMessage(),
    ], 500);
}

