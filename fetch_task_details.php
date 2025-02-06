<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "task_tracker");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$taskId = $_GET['id']; // Get the task ID from the GET request

$sql = "SELECT * FROM tasks WHERE id = $taskId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $task = $result->fetch_assoc(); // Fetch the task details
    echo json_encode($task); // Return task details as JSON
} else {
    echo json_encode(['error' => 'Task not found']); // Return an error if task not found
}

$conn->close();
?>
