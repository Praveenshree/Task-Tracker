<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get task ID from URL

    // Sanitize the ID to prevent SQL injection
    $id = $conn->real_escape_string($id);

    // Fetch the task data from the database
    $sql = "SELECT * FROM tasks WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        echo "Task not found!";
        exit;
    }
} else {
    echo "ID not specified!";
    exit;
}

// Handle form submission for task update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = $_POST['day'];
    $module = $_POST['module'];
    $task = $_POST['task'];
    $task_type = $_POST['task_type'];
    $priority = $_POST['priority'];
    $expected_delivery_date = $_POST['expected_delivery_date'];
    $deep_description = $_POST['deep_description'];

    // SQL query to update the task
    $update_sql = "UPDATE tasks SET 
                    day='$day', 
                    module='$module', 
                    task='$task', 
                    task_type='$task_type', 
                    priority='$priority',
                    expected_delivery_date='$expected_delivery_date', 
                    deep_description='$deep_description' 
                    WHERE id='$id'";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: index.php");  // Redirect to the tasks list page after update
        exit; // Make sure no further code executes
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Navigation Bar (Optional)
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Task Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Edit Task</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav> -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="mb-4">Edit Task</h2>
        <form action="edit_task.php?id=<?php echo $task['id']; ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo $task['id']; ?>" />

            <div class="mb-3">
                <label for="day" class="form-label">Day:</label>
                <input type="text" class="form-control" id="day" name="day" value="<?php echo $task['day']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="module" class="form-label">Module:</label>
                <input type="text" class="form-control" id="module" name="module" value="<?php echo $task['module']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="task" class="form-label">Task:</label>
                <input type="text" class="form-control" id="task" name="task" value="<?php echo $task['task']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="task_type" class="form-label">Task Type:</label>
                <!-- <input type="text" class="form-control" id="task_type" name="task_type" value="<?php echo $task['task_type']; ?>" required> -->
                <select class="form-control" name="task_type">
                            <option>Bug</option>
                            <option>Enhancement</option>
                            <option>Feature</option>
                        </select>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Priority:</label>
                <!-- <input type="text" class="form-control" id="priority" name="priority" value="<?php echo $task['priority']; ?>" required> -->
                <select class="form-control" name="priority">
                            <option>High</option>
                            <option>Medium</option>
                            <option>Low</option>
                        </select>
            </div>

            <div class="mb-3">
                <label for="expected_delivery_date" class="form-label">Expected Delivery Date:</label>
                <input type="date" class="form-control" id="expected_delivery_date" name="expected_delivery_date" value="<?php echo $task['expected_delivery_date']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="deep_description" class="form-label">Deep Description:</label>
                <textarea class="form-control" id="deep_description" name="deep_description" rows="4" required><?php echo $task['deep_description']; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Task</button>
        </form>
    </div>

    <!-- Bootstrap JS (Optional, for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>