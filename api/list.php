<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

try {
    $pdo = getConnection();
    $statement = $pdo->query(
        'SELECT id, title, is_completed, created_at
         FROM tasks
         ORDER BY created_at DESC, id DESC'
    );

    $tasks = array_map(static function (array $task): array {
        return [
            'id' => (int) $task['id'],
            'title' => $task['title'],
            'is_completed' => (bool) $task['is_completed'],
            'created_at' => $task['created_at'],
        ];
    }, $statement->fetchAll());

    sendJson([
        'success' => true,
        'tasks' => $tasks,
    ]);
} catch (Throwable $error) {
    handleApiError($error);
}

