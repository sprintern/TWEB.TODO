const form = document.getElementById('task-form');
const input = document.getElementById('task-input');
const list = document.getElementById('tasks');
const statusMessage = document.getElementById('status-message');
let lastTasksHash = '';
const tasksChannel = 'BroadcastChannel' in window
    ? new BroadcastChannel('todo_tasks_changed')
    : null;

function setStatus(message, isError = false) {
    statusMessage.textContent = message;
    statusMessage.className = isError ? 'error' : '';
}

function notifyTasksChanged() {
    const message = String(Date.now());

    if (tasksChannel) {
        tasksChannel.postMessage(message);
    }

    localStorage.setItem('todo_tasks_changed_at', message);
}

async function requestApi(url, data) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });
    const result = await response.json();

    if (!response.ok || !result.success) {
        throw new Error(result.message || 'Request failed.');
    }

    return result;
}

function createTaskElement(task) {
    const li = document.createElement('li');
    li.dataset.id = task.id;
    li.classList.toggle('completed', task.is_completed);

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.checked = task.is_completed;
    checkbox.addEventListener('change', async function() {
        try {
            await requestApi('api/toggle.php', {
                id: task.id,
                is_completed: checkbox.checked,
            });
            li.classList.toggle('completed', checkbox.checked);
            setStatus('');
            notifyTasksChanged();
        } catch (error) {
            checkbox.checked = !checkbox.checked;
            setStatus(error.message, true);
        }
    });

    const label = document.createElement('label');
    label.textContent = task.title;

    const button = document.createElement('button');
    button.type = 'button';
    button.textContent = 'Delete';
    button.addEventListener('click', async function() {
        try {
            await requestApi('api/delete.php', { id: task.id });
            li.remove();
            lastTasksHash = '';
            setStatus('Task deleted.');
            notifyTasksChanged();
            loadTasks({ silent: true });
        } catch (error) {
            setStatus(error.message, true);
        }
    });

    li.append(checkbox, label, button);

    return li;
}

async function loadTasks(options = {}) {
    const silent = options.silent === true;

    try {
        const response = await fetch('api/list.php');
        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Could not load tasks.');
        }

        const nextTasksHash = JSON.stringify(result.tasks);
        if (nextTasksHash === lastTasksHash) {
            return;
        }

        lastTasksHash = nextTasksHash;
        list.innerHTML = '';
        result.tasks.forEach(function(task) {
            list.appendChild(createTaskElement(task));
        });

        if (!silent) {
            setStatus(result.tasks.length ? '' : 'No tasks yet.');
        }
    } catch (error) {
        if (!silent) {
            setStatus(error.message, true);
        }
    }
}

form.addEventListener('submit', async function(event) {
    event.preventDefault();

    const taskText = input.value.trim();
    if (taskText === '') return;

    try {
        const result = await requestApi('api/add.php', { title: taskText });
        list.prepend(createTaskElement(result.task));
        lastTasksHash = '';
        input.value = '';
        setStatus('Task added.');
        notifyTasksChanged();
        loadTasks({ silent: true });
    } catch (error) {
        setStatus(error.message, true);
    }
});

loadTasks();
setInterval(function() {
    loadTasks({ silent: true });
}, 1000);

window.addEventListener('focus', function() {
    loadTasks({ silent: true });
});

if (tasksChannel) {
    tasksChannel.addEventListener('message', function() {
        loadTasks({ silent: true });
    });
}

window.addEventListener('storage', function(event) {
    if (event.key === 'todo_tasks_changed_at') {
        loadTasks({ silent: true });
    }
});
