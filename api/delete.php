<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

try {
    $data = readJsonBody();
    $id = (int) ($data['id'] ?? 0);

    if ($id <= 0) {
        sendJson([
            'success' => false,
            'message' => 'Task id is required.',
        ], 422);
    }

    $pdo = getConnection();
    $statement = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
    $statement->execute(['id' => $id]);

    sendJson([
        'success' => true,
    ]);
} catch (Throwable $error) {
    handleApiError($error);
}

