// Open Add Task Modal
function openAddTaskModal() {
    document.getElementById('add-task-modal').style.display = 'block';
}

// Close Add Task Modal
function closeAddTaskModal() {
    document.getElementById('add-task-modal').style.display = 'none';
}

// Update Task Status
function updateStatus(taskId, status) {
    if (status === 'In-Progress') {
        const reason = prompt('Enter the reason for not completing the task:');
        if (reason) {
            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${taskId}&status=${status}&remarks=${reason}`,
            }).then(() => location.reload());
        }
    } else {
        fetch('update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${taskId}&status=${status}`,
        }).then(() => location.reload());
    }
}

// Edit Task
function editTask(taskId) {
    window.location.href = `edit_task.php?id=${taskId}`;
}

// Delete Task
function deleteTask(taskId) {
    if (confirm('Are you sure you want to delete this task?')) {
        fetch(`delete_task.php?id=${taskId}`).then(() => location.reload());
    }
}

// Search and Filter Tasks
document.getElementById('search').addEventListener('input', filterTasks);
document.getElementById('filter-status').addEventListener('change', filterTasks);
document.getElementById('filter-priority').addEventListener('change', filterTasks);

function filterTasks() {
    const searchText = document.getElementById('search').value.toLowerCase();
    const filterStatus = document.getElementById('filter-status').value;
    const filterPriority = document.getElementById('filter-priority').value;

    const rows = document.querySelectorAll('#task-table tbody tr');
    rows.forEach(row => {
        const module = row.cells[1].textContent.toLowerCase();
        const task = row.cells[2].textContent.toLowerCase();
        const status = row.cells[7].textContent;
        const priority = row.cells[4].textContent;

        const matchesSearch = module.includes(searchText) || task.includes(searchText);
        const matchesStatus = filterStatus ? status === filterStatus : true;
        const matchesPriority = filterPriority ? priority === filterPriority : true;

        if (matchesSearch && matchesStatus && matchesPriority) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}