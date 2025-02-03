<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Task Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

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
                <th>Priority</th>
                <th>Expected Delivery</th>
                <th>Deep Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="taskTableBody">
            <!-- Tasks will be dynamically inserted here -->
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        // Submit Task
        $('#taskForm').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'add_task.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response){
                    alert('Task Added Successfully!');
                    loadTasks();
                }
            });
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
            alert(response);  // Show response message for debugging
            loadTasks();  // Refresh the task list after update
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
                    alert(response);
                    loadTasks();
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
                });
            }
        });
    });
    $(document).on('click', '.editTask', function(){
    let taskId = $(this).data('id');
    
    // Fetch task details from the server (e.g., using AJAX)
    $.ajax({
        url: 'fetch_tasks.php',
        type: 'GET',
        data: { id: taskId },
        success: function(response) {
            let task = JSON.parse(response);
            
            // Fill the task form with the existing task details
            $('input[name="day"]').val(task.day);
            $('input[name="module"]').val(task.module);
            $('textarea[name="task"]').val(task.task);
            $('select[name="task_type"]').val(task.task_type);
            $('select[name="priority"]').val(task.priority);
            $('input[name="expected_delivery_date"]').val(task.expected_delivery_date);
            $('textarea[name="deep_description"]').val(task.deep_description);
            $('input[name="status"]').val(task.status);
            
            // Change the form action to update the task
            $('#taskForm').attr('action', 'update_task.php?id=' + taskId);
        },
        error: function(xhr, status, error) {
            alert('Error fetching task details: ' + error);
        }
    });
});



</script>
</body>
</html>
