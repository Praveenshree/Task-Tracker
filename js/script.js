document.addEventListener("DOMContentLoaded", function() {
    // Make sure the element exists before attaching the event listener
    const button = document.getElementById('submitButton'); // Replace 'submitButton' with the actual ID or class of your button

    if (button) {
        button.addEventListener('click', function() {
            // Your code for the button click
            console.log('Button clicked!');
        });
    } else {
        console.warn('submitButton element not found!');
    }

    // Example of other event listeners if you have multiple elements
    const anotherButton = document.getElementById('anotherButton'); // Replace with your actual element's ID
    if (anotherButton) {
        anotherButton.addEventListener('click', function() {
            console.log('Another button clicked!');
        });
    }
});
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
document.addEventListener("DOMContentLoaded", function() {
    // Fetch task data from the server
    fetch('fetch_tasks.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json(); // parse JSON from the response
        })
        .then(data => {
            // Populate the table with fetched data
            populateTasksTable(data);
            // Optionally, process data for charts here too
            // const statusData = processStatusData(data);
            // renderStatusChart(statusData);
        })
        .catch(error => console.error('Error fetching task data:', error));
});

/**
 * Populates the tasks table using JSON data.
 * @param {Array} data - The array of task objects.
 */
function populateTasksTable(data) {
    const tbody = document.querySelector('#tasksTable tbody');
    tbody.innerHTML = ""; // clear existing rows if any

    data.forEach(task => {
        // Create a new row
        const tr = document.createElement("tr");

        // Build the row's HTML
        tr.innerHTML = `
            <td>${task.day}</td>
            <td>${task.module}</td>
            <td>${task.task}</td>
            <td>${task.task_type}</td>
            <td>${task.priority}</td>
            <td>${task.expected_delivery_date}</td>
            <td>${task.deep_description}</td>
            <td>${task.status}</td>
            <td>
                <button class="btn btn-success btn-sm submitTask" data-id="${task.id}">Submit</button>
                <button class="btn btn-warning btn-sm inProgressTask" data-id="${task.id}">In-Progress</button>
                <button class="btn btn-primary btn-sm editTask" data-id="${task.id}">Edit</button>
                <button class="btn btn-danger btn-sm deleteTask" data-id="${task.id}">Delete</button>
            </td>
        `;
        // Append the row to the table body
        tbody.appendChild(tr);
    });
}

function loadTasks() {
    $.ajax({
        url: 'fetch_tasks.php',
        type: 'GET',
        success: function(response) {
            var tasks = JSON.parse(response);
            var taskRows = '';
            tasks.forEach(function(task) {
                taskRows += '<tr>';
                taskRows += '<td>' + task.day + '</td>';
                taskRows += '<td>' + task.module + '</td>';
                taskRows += '<td>' + task.task + '</td>';
                taskRows += '<td>' + task.task_type + '</td>';
                taskRows += '<td>' + task.priority + '</td>';
                taskRows += '<td>' + task.expected_delivery_date + '</td>';
                taskRows += '<td>' + task.deep_description + '</td>';
                taskRows += '<td>' + task.status + '</td>';
                taskRows += '<td><button class="btn btn-warning edit-btn" data-id="' + task.id + '">Edit</button> <button class="btn btn-danger delete-btn" data-id="' + task.id + '">Delete</button></td>';
                taskRows += '</tr>';
            });
            $('#taskTableBody').html(taskRows);
        }
    });
}

$('#combinedFilter').on('change', function() {
    let filterValue = $(this).val();
    $.ajax({
        url: 'fetch_tasks.php',
        type: 'GET',
        data: { filter: filterValue },
        success: function(response) {
            $('#taskTableBody').html(response);
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    // Fetch task data from the server (replace with your API endpoint)
    fetch('fetch_tasks.php')
        .then(response => response.json())
        .then(data => {
            // Process data for charts
            const statusData = processStatusData(data);
            const priorityData = processPriorityData(data);
            const taskTypeData = processTaskTypeData(data);

            // Render charts
            renderStatusChart(statusData);
            renderPriorityChart(priorityData);
            renderTaskTypeChart(taskTypeData);
        })
        .catch(error => console.error('Error fetching task data:', error));
});

// Function to process task status data
function processStatusData(data) {
    const statusCounts = {
        Pending: 0,
        InProgress: 0,
        Completed: 0
    };

    data.forEach(task => {
        if (task.status === 'Pending') statusCounts.Pending++;
        else if (task.status === 'In-Progress') statusCounts.InProgress++;
        else if (task.status === 'Completed') statusCounts.Completed++;
    });

    return {
        labels: Object.keys(statusCounts),
        datasets: [{
            label: 'Task Status',
            data: Object.values(statusCounts),
            backgroundColor: ['#FF6384', '#36A2EB', '#4BC0C0'],
            hoverOffset: 4
        }]
    };
}

// Function to process task priority data
function processPriorityData(data) {
    const priorityCounts = {
        High: 0,
        Medium: 0,
        Low: 0
    };

    data.forEach(task => {
        if (task.priority === 'High') priorityCounts.High++;
        else if (task.priority === 'Medium') priorityCounts.Medium++;
        else if (task.priority === 'Low') priorityCounts.Low++;
    });

    return {
        labels: Object.keys(priorityCounts),
        datasets: [{
            label: 'Task Priority',
            data: Object.values(priorityCounts),
            backgroundColor: ['#FF6384', '#FFCE56', '#36A2EB'],
            hoverOffset: 4
        }]
    };
}

// Function to process task type data
function processTaskTypeData(data) {
    const taskTypeCounts = {
        Bug: 0,
        Enhancement: 0,
        Feature: 0
    };

    data.forEach(task => {
        if (task.task_type === 'Bug') taskTypeCounts.Bug++;
        else if (task.task_type === 'Enhancement') taskTypeCounts.Enhancement++;
        else if (task.task_type === 'Feature') taskTypeCounts.Feature++;
    });

    return {
        labels: Object.keys(taskTypeCounts),
        datasets: [{
            label: 'Task Type',
            data: Object.values(taskTypeCounts),
            backgroundColor: ['#FF6384', '#FFCE56', '#4BC0C0'],
            hoverOffset: 4
        }]
    };
}

// Function to render the Task Status chart
function renderStatusChart(data) {
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' tasks';
                        }
                    }
                }
            }
        }
    });
}

// Function to render the Task Priority chart
function renderPriorityChart(data) {
    const ctx = document.getElementById('priorityChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: data,
        options: {
            responsive: true
        }
    });
}

// Function to render the Task Type chart
function renderTaskTypeChart(data) {
    const ctx = document.getElementById('taskTypeChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: data,
        options: {
            responsive: true
        }
    });
}

function loadTasks() {
    $.ajax({
        url: 'fetch_tasks.php', // Ensure this file returns the task list
        type: 'GET',
        success: function(response) {
            $('#taskTableBody').html(response);
            updateCharts(); // Refresh the charts after loading tasks
        },
        error: function(xhr, status, error) {
            console.error("Error loading tasks:", error);
        }
    });
}

function updateCharts() {
    $.ajax({
        url: 'fetch_task_data.php', // Ensure this file returns JSON stats
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (statusChart && priorityChart && taskTypeChart) {
                statusChart.data.datasets[0].data = [data.pending, data.inProgress, data.completed];
                priorityChart.data.datasets[0].data = [data.high, data.medium, data.low];
                taskTypeChart.data.datasets[0].data = [data.bug, data.enhancement, data.feature];

                statusChart.update();
                priorityChart.update();
                taskTypeChart.update();
            }
        },
        error: function(xhr, status, error) {
            console.error("Error updating charts:", error);
        }
    });
}

// Function to check tasks nearing their due date
function checkDueDates() {
    $.ajax({
        url: 'fetch_tasks.php', // Fetch all tasks with their due dates
        type: 'GET',
        success: function(response) {
            const tasks = JSON.parse(response); // Assuming your PHP returns JSON with task details

            // Loop through the tasks to check their due date
            tasks.forEach(task => {
                const currentDate = new Date();
                const dueDate = new Date(task.expected_delivery_date);
                const timeDifference = dueDate - currentDate;

                // Check if the task is within 1 day of its due date
                if (timeDifference <= 24 * 60 * 60 * 1000 && timeDifference > 0) {
                    // Display a notification if the task is nearing the due date
                    displayDueDateReminder(task);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching tasks:", error);
        }
    });
}

// Function to display a reminder notification
function displayDueDateReminder(task) {
    // Customize the notification to show the task name and due date
    alert(`Reminder: The task "${task.task}" is nearing its due date of ${task.expected_delivery_date}.`);
}

// Set an interval to check for due date reminders every hour (3600000 ms = 1 hour)
setInterval(checkDueDates, 3600000);

// Also, call it when the page loads initially
$(document).ready(function() {
    checkDueDates(); // Run this on page load
});

function displayDueDateReminder(task) {
    toastr.info(`Reminder: The task "${task.task}" is nearing its due date of ${task.expected_delivery_date}.`, 'Task Reminder', {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000
    });
}