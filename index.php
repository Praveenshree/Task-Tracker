<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Task Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>


<style>/* Custom CSS for chart container */
canvas {
    width: 100% !important;
    height: auto !important;
}

/* Optional: Make the chart containers responsive */
@media (max-width: 768px) {
    .col-md-4 {
        width: 100%;
    }
}
</style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Employee Task Tracker</h2>
    
    <!-- Combined Filter (Priority, Status, Day) and Search Box with Add Task on the Right -->
    <div class="row mb-3">
        <div class="col-md-6">
            <select id="combinedFilter" class="form-control">
                <option value="">Filter by</option>
                <option value="priority-high">High Priority</option>
                <option value="priority-medium">Medium Priority</option>
                <option value="priority-low">Low Priority</option>
                <option value="status-pending">Pending Status</option>
                <option value="status-inprogress">In-Progress Status</option>
                <option value="status-completed">Completed Status</option>
                <option value="day-today">Today</option>
                <option value="day-thisweek">This Week</option>
                <option value="day-thismonth">This Month</option>
            </select>
        </div>

        <!-- Search Box (Left) and Add Task Button (Right) -->
        <div class="col-md-6 d-flex justify-content-between">
            <input type="text" id="searchBox" class="form-control" placeholder="Search tasks..." style="flex-grow: 1;"/>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Add Task
                </button>
                <div class="dropdown-menu p-4" style="width: 300px;">
                    <form id="taskForm">
                        <label>Day:</label>
                        <input type="date" class="form-control" name="day" required>
                        <label>Module:</label>
                        <input type="text" class="form-control" name="module" required>
                        <label>Task:</label>
                        <textarea class="form-control" name="task" required></textarea>
                        <label>Task Type:</label>
                        <select class="form-control" name="task_type">
                            <option>Bug</option>
                            <option>Enhancement</option>
                            <option>Feature</option>
                        </select>
                        <label>Priority:</label>
                        <select class="form-control" name="priority">
                            <option>High</option>
                            <option>Medium</option>
                            <option>Low</option>
                        </select>
                        <label>Expected Delivery:</label>
                        <input type="date" class="form-control" name="expected_delivery_date" required>
                        <label>Deep Description:</label>
                        <textarea class="form-control" name="deep_description"></textarea>
                        <input type="hidden" name="status" value="Pending">
                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Save Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Task Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Day</th>
                <th>Module</th>
                <th>Task</th>
                <th>Task Type</th>
                <th>
                Priority 
                <button class="btn btn-sm btn-light sort-btn" data-sort="priority">↕</button>
            </th>
            <th>
                Expected Delivery 
                <button class="btn btn-sm btn-light sort-btn" data-sort="expected_delivery">↕</button>
            </th>
                <th>Deep Description</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="taskTableBody">
            <!-- Tasks will be dynamically inserted here -->
        </tbody>
    </table>
</div>
<!-- Pie Charts Section -->
<div class="row mt-4 justify-content-center">
    <div class="col-md-3 col-sm-8 mb-3">
        <h4>Status Distribution</h4>
        <canvas id="statusPieChart"></canvas>
    </div>
    <div class="col-md-3 col-sm-8 mb-3">
        <h4>Priority Distribution</h4>
        <canvas id="priorityPieChart"></canvas>
    </div>
    <div class="col-md-3 col-sm-8 mb-3">
        <h4>Task Type Distribution</h4>
        <canvas id="taskTypePieChart"></canvas>
    </div>
</div>


<script src="js\script.js"></script> <!-- Make sure this file is linked in your HTML -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    loadTasks(); // Load tasks when the page loads
    updateCharts(); // Load charts when the page loads
});
</script>

<script>


    $(document).ready(function(){
        function loadTasks(){
            $.ajax({
                url: 'fetch_tasks.php',
                type: 'GET',
                success: function(response){
                    $('#taskTableBody').html(response);
                }
            });
        }

        // Load tasks on page load
        loadTasks();
        updateCharts();

        // // Submit Task
        // $('#taskForm').submit(function(e){
        //     e.preventDefault();
        //     $.ajax({
        //         url: 'add_task.php',
        //         type: 'POST',
        //         data: $(this).serialize(),
        //         success: function(response){
        //             alert('Task Added Successfully!');
        //             loadTasks();
        //             updateCharts();
        //         }
        //     });
        // });


        $(document).ready(function() {
    // Ensure the task form is being submitted properly
    $('#taskForm').submit(function(e){
        e.preventDefault(); // Prevent the default form submission

        // Collect form data
        var formData = $(this).serialize();

        // Make AJAX request to add the task
        $.ajax({
            url: 'add_task.php', // Your server-side PHP file that handles task addition
            type: 'POST',
            data: formData,
            success: function(response){
                alert('Task Added Successfully!');
                loadTasks();  // Reload the task list
                updateCharts(); // Refresh the charts
                $('#taskForm')[0].reset();  // Clear the form after submission
            },
            error: function(xhr, status, error) {
                alert('Error adding task: ' + error);
            }
        });
    });

    // Load tasks when the page loads
    loadTasks();
    updateCharts();
});

        

        // Filter tasks based on selected option
        $('#combinedFilter').on('change', function(){
            let filterValue = $(this).val();
            $("#taskTableBody tr").each(function(){
                let row = $(this);
                let priority = row.find("td:eq(4)").text().toLowerCase();
                let status = row.find("td:eq(7)").text().toLowerCase();
                let day = new Date(row.find("td:eq(0)").text()).getTime();
                let today = new Date().setHours(0, 0, 0, 0);
                let startOfWeek = new Date().setDate(new Date().getDate() - new Date().getDay());
                let startOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1).getTime();

                let match = false;

                // Priority Filter
                if (filterValue.includes('priority') && !filterValue.includes(priority)) {
                    match = false;
                } else if (filterValue.includes('status') && !filterValue.includes(status)) {
                    match = false;
                } else if (filterValue.includes('day-today') && day !== today) {
                    match = false;
                } else if (filterValue.includes('day-thisweek') && (day < startOfWeek)) {
                    match = false;
                } else if (filterValue.includes('day-thismonth') && (day < startOfMonth)) {
                    match = false;
                } else {
                    match = true;
                }

                $(this).toggle(match);
            });
        });

        // Search functionality
        $('#searchBox').on('input', function(){
            let searchValue = $(this).val().toLowerCase();
            $("#taskTableBody tr").each(function(){
                let row = $(this);
                let taskName = row.find("td:eq(2)").text().toLowerCase();
                let moduleName = row.find("td:eq(1)").text().toLowerCase();
                let match = taskName.includes(searchValue) || moduleName.includes(searchValue);
                $(this).toggle(match);
            });
        });

        $(document).on('click', '.submitTask', function(){
    let taskId = $(this).data('id');
    
    $.ajax({
        url: 'update_task_status.php',
        type: 'POST',
        data: { id: taskId, status: 'Completed' },
        success: function(response) {
            alert('Task Completed successfully');  // Show response message for debugging
            loadTasks();  // Refresh the task list after update
            updateCharts();
        },
        error: function(xhr, status, error) {
            alert("Error: " + error); // Show error message
        }
    });
});


        // Mark as In-Progress with Remarks
        $(document).on('click', '.inProgressTask', function(){
            let taskId = $(this).data('id');
            let remarks = prompt("Enter reason for not completing the task:");
            if (remarks) {
                $.post('update_task_status.php', { id: taskId, status: 'In-Progress', remarks: remarks }, function(response){
                    alert('In-Progress');
                    loadTasks();
                    updateCharts();
                });
            }
        });
 $(document).on('click', '.editTask', function(){
    let taskId = $(this).data('id');
    window.location.href = 'edit_task.php?id=' + taskId;  // Redirect to the edit page with the task ID
});

        // Delete Task
        $(document).on('click', '.deleteTask', function(){
            if (confirm("Are you sure you want to delete this task?")) {
                let taskId = $(this).data('id');
                $.post('delete_task.php', { id: taskId }, function(response){
                    alert(response);
                    loadTasks();
                    updateCharts();
                });
            }
        });
    });
   


$(document).ready(function () {
    function loadTasks() {
        $.ajax({
            url: 'fetch_tasks.php', // Modify to the correct URL if needed
            type: 'GET',
            success: function (response) {
                // Update the table with tasks
                $('#taskTableBody').html(response);

                // Call function to update pie charts
                updatePieCharts();
            }
        });
    }

    function updatePieCharts() {
        $.ajax({
            url: 'fetch_task_data.php', // New PHP file to fetch task data for the charts
            type: 'GET',
            success: function (response) {
                const taskData = JSON.parse(response);

                const statusData = {
                    labels: ['Pending', 'In-Progress', 'Completed'],
                    datasets: [{
                        data: [taskData.pending, taskData.inProgress, taskData.completed],
                        backgroundColor: ['#ffcc00', '#007bff', '#28a745'],
                        hoverOffset: 4
                    }]
                };

                const priorityData = {
                    labels: ['High', 'Medium', 'Low'],
                    datasets: [{
                        data: [taskData.highPriority, taskData.mediumPriority, taskData.lowPriority],
                        backgroundColor: ['#ff5733', '#ffbf00', '#7bed9f'],
                        hoverOffset: 4
                    }]
                };

                const taskTypeData = {
                    labels: ['Bug', 'Enhancement', 'Feature'],
                    datasets: [{
                        data: [taskData.bug, taskData.enhancement, taskData.feature],
                        backgroundColor: ['#e74c3c', '#f39c12', '#3498db'],
                        hoverOffset: 4
                    }]
                };

                // Status Pie Chart
                new Chart(document.getElementById('statusPieChart'), {
                    type: 'pie',
                    data: statusData,
                });

                // Priority Pie Chart
                new Chart(document.getElementById('priorityPieChart'), {
                    type: 'pie',
                    data: priorityData,
                });

                // Task Type Pie Chart
                new Chart(document.getElementById('taskTypePieChart'), {
                    type: 'pie',
                    data: taskTypeData,
                });
            }
        });
    }

    // Initial load of tasks and charts
    loadTasks();
});

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
        success: function (data) {
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

$(document).ready(function() {
    loadTasks(); // Load tasks when the page loads
    updateCharts(); // Load charts when the page loads
});

$(document).ready(function() {
    // Sorting function
    function sortTable(column, ascending) {
        let rows = $('#taskTableBody tr').get();
        rows.sort(function(a, b) {
            let valA = $(a).find(`td:eq(${column})`).text().trim();
            let valB = $(b).find(`td:eq(${column})`).text().trim();

            if (column === 4) { // Sorting for priority
                let priorityOrder = { "High": 1, "Medium": 2, "Low": 3 };
                return (priorityOrder[valA] - priorityOrder[valB]) * (ascending ? 1 : -1);
            } else if (column === 5) { // Sorting for expected delivery date
                let dateA = new Date(valA);
                let dateB = new Date(valB);
                return (dateA - dateB) * (ascending ? 1 : -1);
            } 
        });

        $.each(rows, function(index, row) {
            $('#taskTableBody').append(row);
        });
    }

    // Click event for sorting buttons
    let sortStates = {}; // Store sorting states
    $('.sort-btn').on('click', function() {
        let column = $(this).data('sort') === 'priority' ? 4 : 5;
        sortStates[column] = !sortStates[column]; // Toggle sorting order
        sortTable(column, sortStates[column]);
    });
});


</script>
<script src="js/notification.js"></script>
<script src="js/script.js"></script>
</body>
</html>
