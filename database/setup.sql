CREATE DATABASE IF NOT EXISTS tweb_todo_lab4
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE tweb_todo_lab4;

CREATE TABLE IF NOT EXISTS tasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    is_completed TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO tasks (id, title, is_completed) VALUES
    (1, 'Buy groceries', 0),
    (2, 'Do homework', 0),
    (3, 'Call a friend', 1);

