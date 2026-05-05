<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

$pdo = getConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $title = trim((string) ($_POST['title'] ?? ''));

        if ($title === '') {
            $error = 'Task title is required.';
        } else {
            $statement = $pdo->prepare('INSERT INTO tasks (title) VALUES (:title)');
            $statement->execute(['title' => $title]);
            header('Location: index.php');
            exit;
        }
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $statement = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
            $statement->execute(['id' => $id]);
        }

        header('Location: index.php');
        exit;
    }

    if ($action === 'toggle') {
        $id = (int) ($_POST['id'] ?? 0);
        $isCompleted = isset($_POST['is_completed']) ? 1 : 0;

        if ($id > 0) {
            $statement = $pdo->prepare(
                'UPDATE tasks SET is_completed = :is_completed WHERE id = :id'
            );
            $statement->execute([
                'id' => $id,
                'is_completed' => $isCompleted,
            ]);
        }

        header('Location: index.php');
        exit;
    }
}

$statement = $pdo->query(
    'SELECT id, title, is_completed, created_at
     FROM tasks
     ORDER BY created_at DESC, id DESC'
);
$tasks = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Todo List</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>

    <header>
        <h1>My Todo List</h1>
    </header>

    <main>
        <section id="add-task">
            <h2>Add a task</h2>
            <form method="post" action="index.php">
                <input type="hidden" name="action" value="add">
                <input type="text" name="title" id="task-input" placeholder="Enter a task..." autocomplete="off" />
                <button type="submit">Add</button>
            </form>
            <?php if ($error !== ''): ?>
                <p id="status-message" class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </section>

        <section id="task-list">
            <h2>Tasks</h2>
            <ul>
                <?php foreach ($tasks as $task): ?>
                    <li class="<?= (int) $task['is_completed'] === 1 ? 'completed' : '' ?>">
                        <form method="post" action="index.php" class="inline-form">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?= (int) $task['id'] ?>">
                            <input
                                type="checkbox"
                                name="is_completed"
                                onchange="this.form.submit()"
                                <?= (int) $task['is_completed'] === 1 ? 'checked' : '' ?>
                            >
                        </form>
                        <label><?= htmlspecialchars($task['title']) ?></label>
                        <form method="post" action="index.php" class="inline-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $task['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>Todo List &copy; 2026</p>
    </footer>

</body>
</html>

