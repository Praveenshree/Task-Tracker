<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task_tracker"; // Change if needed

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle task submission
if (isset($_GET['submit_id'])) {
    $taskId = $_GET['submit_id'];

    // Update task status to 'Completed' (not 'Submitted')
    $sql = "UPDATE tasks SET status = 'Completed' WHERE id = $taskId";

    if ($conn->query($sql) === TRUE) {
        echo "Task marked as completed successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close connection and exit
    $conn->close();
    exit; // Stop script execution here for the submit task part
}

// Fetch tasks from the database
$sql = "SELECT * FROM tasks ORDER BY expected_delivery_date ASC";
$result = $conn->query($sql);

$output = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $disabled = $row['status'] === 'Completed' ? 'disabled' : ''; // Disable if status is 'Completed'

        $output .= "<tr>
        <td>{$row['day']}</td>
        <td>{$row['module']}</td>
        <td>{$row['task']}</td>
        <td>{$row['task_type']}</td>
        <td>{$row['priority']}</td>
        <td>{$row['expected_delivery_date']}</td>
        <td>{$row['deep_description']}</td>
        <td>{$row['status']}</td>
        <td>{$row['remarks']}</td>
        <td>
            <div class='btn-group' role='group'>
                <button class='btn btn-success btn-sm submitTask' data-id='{$row['id']}' onclick='confirmSubmit({$row['id']})' $disabled>Submit</button>
                <button class='btn btn-warning btn-sm inProgressTask' data-id='{$row['id']}' $disabled>Delay</button>
                <button class='btn btn-primary btn-sm editTask' data-id='{$row['id']}' $disabled>Edit</button>
                <button class='btn btn-danger btn-sm deleteTask' data-id='{$row['id']}' $disabled>Delete</button>
            </div>
        </td>
    </tr>";
    }
} else {
    $output .= "<tr><td colspan='9' class='text-center'>No tasks available</td></tr>";
}

echo $output;
$conn->close();
?>

<!-- JavaScript for handling the confirmation and AJAX request -->
<script>
    function confirmSubmit(taskId) {
        var confirmAction = confirm("Are you sure you want to submit this task?");
        if (confirmAction) {
            // Disable all buttons related to this task
            var taskButtons = document.querySelectorAll('[data-id="' + taskId + '"]');
            taskButtons.forEach(function(button) {
                button.disabled = true;  // Disable the button
                button.classList.add('disabled');  // Optionally add a 'disabled' class for styling
            });

            // Make AJAX request to update task status to "Completed"
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_tasks.php?submit_id=' + taskId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("Task ID " + taskId + " marked as completed.");
                    // Optionally, update the task status in the UI
                } else {
                    console.log("Error marking task as completed.");
                }
            };
            xhr.send();

        } else {
            console.log("Task submission canceled.");
        }
    }
</script>
