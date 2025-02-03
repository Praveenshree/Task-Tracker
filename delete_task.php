<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task_tracker"; // Change if needed

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$sql = "DELETE FROM tasks WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Task deleted successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
