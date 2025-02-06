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
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

<!-- jQuery and DataTable Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<!-- To allow export functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.2.0/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>



<style>/* Custom CSS for chart container */

/* Prevent wrapping in Day and Expected Delivery columns */
#taskTable td:nth-child(1),
#taskTable td:nth-child(6) {
    white-space: nowrap; /* Prevent text wrapping */
}

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
/* Add margin between action buttons */
.table .btn {
    margin-right: 5px; /* Adjust the value to your preference */
}

</style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Employee Task Tracker</h2>
    
    <div class="row mb-3">
    <div class="col-md-6">
  <!-- Row containing Filter, Search, and Add Task buttons -->
  <div class="row mb-3">
        <div class="col-md-4 d-flex align-items-center">
            <!-- Filter Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Filter by
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" data-value="priority-high">High Priority</a></li>
                    <li><a class="dropdown-item" href="#" data-value="priority-medium">Medium Priority</a></li>
                    <li><a class="dropdown-item" href="#" data-value="priority-low">Low Priority</a></li>
                    <li><a class="dropdown-item" href="#" data-value="status-pending">Pending Status</a></li>
                    <li><a class="dropdown-item" href="#" data-value="status-inprogress">In-Progress Status</a></li>
                    <li><a class="dropdown-item" href="#" data-value="status-completed">Completed Status</a></li>
                    <li><a class="dropdown-item" href="#" data-value="day-today">Today</a></li>
                    <li><a class="dropdown-item" href="#" data-value="day-thisweek">This Week</a></li>
                    <li><a class="dropdown-item" href="#" data-value="day-thismonth">This Month</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-4 d-flex align-items-center ">
            <!-- Search Box -->
<!-- Search Box (Left) -->
<input type="text" id="searchBox" class="form-control" placeholder="Search tasks..." style="flex-grow: 1;"/>
        </div>

        <div class="col-md-4 d-flex align-items-center justify-content-end">
            <!-- Add Task Dropdown -->
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Add Task
                </button>
                <div class="dropdown-menu p-4" style="width: 300px;">
                    <form id="taskForm">
                        <!-- Add Task Form Fields -->
                        <label>Day:    </label>
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

    <!-- Table for displaying tasks -->
    <table id="taskTable" class="table table-bordered">
    <thead>
    <tr>
        <th data-sortable="true">Day</th>
        <th data-sortable="true">Module</th>
        <th data-sortable="true">Task</th>
        <th data-sortable="true">Task Type</th>
        <th data-sortable="true">Priority</th>
        <th data-sortable="true">Expected Delivery</th>
        <th data-sortable="true">Deep Description</th>
        <th data-sortable="true">Status</th>
        <th data-sortable="true">Remarks</th>
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
    <div class="col-md-4 col-sm-6 mb-3">
        <h4>Status Distribution</h4>
        <canvas id="statusPieChart"></canvas>
    </div>
    <div class="col-md-4 col-sm-6 mb-3">
        <h4>Priority Distribution</h4>
        <canvas id="priorityPieChart"></canvas>
    </div>
    <div class="col-md-4 col-sm-6 mb-3">
        <h4>Task Type Distribution</h4>
        <canvas id="taskTypePieChart"></canvas>
    </div>
</div>

<!-- Overlay for dimming background -->
<div id="editTaskOverlay"></div>

<!-- Edit Task Pop-up Form -->
<div id="editTaskPopup">
    <div class="edit-popup-content">
        <span class="close-btn" onclick="closeEditPopup()">×</span>
        <h5>Edit Task</h5>
        <form id="editTaskForm">
            <input type="hidden" id="taskId" name="taskId">

            <label for="editDay">Day:</label>
            <input type="date" id="editDay" name="day" required>

            <label for="editModule">Module:</label>
            <input type="text" id="editModule" name="module" required>

            <label for="editTask">Task:</label>
            <textarea id="editTask" name="task" required></textarea>

            <label for="editTaskType">Task Type:</label>
            <select id="editTaskType" name="task_type" required>
                <option value="Bug">Bug</option>
                <option value="Enhancement">Enhancement</option>
                <option value="Feature">Feature</option>
            </select>

            <label for="editPriority">Priority:</label>
            <select id="editPriority" name="priority" required>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
            </select>

            <label for="editExpectedDelivery">Expected Delivery:</label>
            <input type="date" id="editExpectedDelivery" name="expected_delivery_date" required>

            <label for="editDeepDescription">Deep Description:</label>
            <textarea id="editDeepDescription" name="deep_description"></textarea>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditPopup()">Cancel</button>
        </form>
    </div>
</div>




<script src="js\script.js"></script> <!-- Make sure this file is linked in your HTML -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>


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

$('#searchBox').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase(); // Get the search term

        // Filter the table rows based on the search term
        $('#taskTableBody tr').each(function() {
            var rowText = $(this).text().toLowerCase(); // Get the text of the entire row

            // Check if the search term exists in the row text
            if (rowText.indexOf(searchTerm) !== -1) {
                $(this).show(); // Show the row if it matches
            } else {
                $(this).hide(); // Hide the row if it doesn't match
            }
        });
    });

      // Filter tasks based on selected option
$('.dropdown-item').on('click', function() {
    let filterValue = $(this).data('value'); // Get the selected filter value
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
        if (filterValue.includes('priority') && !priority.includes(filterValue.split('-')[1])) {
            match = false;
        } 
        // Status Filter
        else if (filterValue.includes('status') && !status.includes(filterValue.split('-')[1])) {
            match = false;
        } 
        // Day Filter
        else if (filterValue === 'day-today' && day !== today) {
            match = false;
        } 
        else if (filterValue === 'day-thisweek' && (day < startOfWeek)) {
            match = false;
        } 
        else if (filterValue === 'day-thismonth' && (day < startOfMonth)) {
            match = false;
        } 
        else {
            match = true;
        }

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
});</script>
<script src="js/notification.js"></script>
<script src="js/script.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("taskTable");
    const headers = table.querySelectorAll("th"); // Select all column headers
    let direction = {}; // Store sorting direction

    headers.forEach((header, columnIndex) => {
        if (header.hasAttribute("data-sortable")) {
            header.addEventListener("click", function () {
                let rows = Array.from(table.rows).slice(1); // Get all rows except header
                let isAscending = direction[columnIndex] = !direction[columnIndex]; // Toggle sorting direction

                // Remove sorting classes from all headers
                headers.forEach(th => th.classList.remove("sorted-asc", "sorted-desc"));

                // Add the relevant class to the clicked header
                if (isAscending) {
                    header.classList.add("sorted-asc");
                } else {
                    header.classList.add("sorted-desc");
                }

                rows.sort((rowA, rowB) => {
                    let cellA = rowA.cells[columnIndex].innerText.trim();
                    let cellB = rowB.cells[columnIndex].innerText.trim();

                    // Detect if sorting column is a Date
                    if (columnIndex === 0 || columnIndex === 5) { 
                        return isAscending ? new Date(cellA) - new Date(cellB) : new Date(cellB) - new Date(cellA);
                    }

                    // Detect if sorting column is a Number
                    if (!isNaN(cellA) && !isNaN(cellB)) {
                        return isAscending ? cellA - cellB : cellB - cellA;
                    }

                    // Default: Sort as text (string)
                    return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
                });

                // Append sorted rows back to table
                rows.forEach(row => table.appendChild(row));
            });
        }
    });
});

// Show the edit pop-up and pre-fill the form
$(document).on('click', '.editTask', function() {
    var taskId = $(this).data('id'); // Get the task ID

    // Fetch task details based on the task ID
    $.ajax({
        url: 'fetch_task_details.php',
        type: 'GET',
        data: { id: taskId },
        success: function(response) {
            var task = JSON.parse(response); // Parse the returned task data

            // Pre-fill the form fields with task data
            $('#taskId').val(task.id);
            $('#editDay').val(task.day);
            $('#editModule').val(task.module);
            $('#editTask').val(task.task);
            $('#editTaskType').val(task.task_type);
            $('#editPriority').val(task.priority);
            $('#editExpectedDelivery').val(task.expected_delivery_date);
            $('#editDeepDescription').val(task.deep_description);

            // Show the pop-up form
            $('#editTaskPopup').fadeIn();
        },
        error: function(xhr, status, error) {
            alert("Error fetching task details: " + error);
        }
    });
});

// Close the edit pop-up
function closeEditPopup() {
    $('#editTaskPopup').fadeOut(); // Hide the pop-up when the cancel button is clicked
}

// Handle form submission for editing the task
$('#editTaskForm').submit(function(e) {
    e.preventDefault(); // Prevent the default form submission

    // Collect the form data
    var formData = $(this).serialize(); // Serialize the form data

    // Send the data to the server to update the task
    $.ajax({
        url: 'update_task.php', // The PHP file to handle task updates
        type: 'POST',
        data: formData, // Send the form data to the server
        success: function(response) {
            alert('Task updated successfully');
            loadTasks(); // Reload the tasks to reflect the changes
            $('#editTaskPopup').fadeOut(); // Close the pop-up after saving changes
        },
        error: function(xhr, status, error) {
            alert('Error updating task: ' + error);
        }
    });
});

</script>
</body>
</html>
