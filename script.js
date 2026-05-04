const form = document.querySelector('form');
const input = document.getElementById('task-input');
const list = document.querySelector('ul');

form.addEventListener('submit', function(event) {
    event.preventDefault();

    const taskText = input.value.trim();
    if (taskText === '') return;

    const li = document.createElement('li');
    li.innerHTML = `
        <input type="checkbox" />
        <label>${taskText}</label>
        <button onclick="this.parentElement.remove()">Delete</button>
    `;

    list.appendChild(li);
    input.value = '';
});