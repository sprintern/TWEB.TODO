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
            <form id="task-form">
                <input type="text" id="task-input" placeholder="Enter a task..." autocomplete="off" />
                <button type="submit">Add</button>
            </form>
            <p id="status-message" role="status"></p>
        </section>

        <section id="task-list">
            <h2>Tasks</h2>
            <ul id="tasks"></ul>
        </section>
    </main>

    <footer>
        <p>Todo List &copy; 2026</p>
    </footer>

    <script src="script.js?v=2"></script>

</body>
</html>
