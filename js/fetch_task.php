<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task_tracker"; // Change if needed

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the task ID from the query parameter
$taskId = $_GET['id'];

// Fetch the task data
$sql = "SELECT * FROM tasks WHERE id = $taskId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $task = $result->fetch_assoc();
    echo json_encode($task);
} else {
    echo json_encode(["error" => "Task not found"]);
}

$conn->close();
?>
