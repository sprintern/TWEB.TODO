<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

try {
    $data = readJsonBody();
    $id = (int) ($data['id'] ?? 0);
    $isCompleted = (bool) ($data['is_completed'] ?? false);

    if ($id <= 0) {
        sendJson([
            'success' => false,
            'message' => 'Task id is required.',
        ], 422);
    }

    $pdo = getConnection();
    $statement = $pdo->prepare(
        'UPDATE tasks
         SET is_completed = :is_completed
         WHERE id = :id'
    );
    $statement->execute([
        'id' => $id,
        'is_completed' => $isCompleted ? 1 : 0,
    ]);

    sendJson([
        'success' => true,
    ]);
} catch (Throwable $error) {
    handleApiError($error);
}

