<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

try {
    $data = readJsonBody();
    $title = trim((string) ($data['title'] ?? ''));

    if ($title === '') {
        sendJson([
            'success' => false,
            'message' => 'Task title is required.',
        ], 422);
    }

    $pdo = getConnection();
    $statement = $pdo->prepare('INSERT INTO tasks (title) VALUES (:title)');
    $statement->execute(['title' => $title]);

    $id = (int) $pdo->lastInsertId();
    $statement = $pdo->prepare(
        'SELECT id, title, is_completed, created_at
         FROM tasks
         WHERE id = :id'
    );
    $statement->execute(['id' => $id]);
    $task = $statement->fetch();

    sendJson([
        'success' => true,
        'task' => [
            'id' => (int) $task['id'],
            'title' => $task['title'],
            'is_completed' => (bool) $task['is_completed'],
            'created_at' => $task['created_at'],
        ],
    ], 201);
} catch (Throwable $error) {
    handleApiError($error);
}

