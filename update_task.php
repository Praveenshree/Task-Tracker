<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "task_tracker");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['taskId'];
    $day = $_POST['day'];
    $module = $_POST['module'];
    $task = $_POST['task'];
    $taskType = $_POST['task_type'];
    $priority = $_POST['priority'];
    $expectedDelivery = $_POST['expected_delivery_date'];
    $deepDescription = $_POST['deep_description'];

    $sql = "UPDATE tasks SET 
            day = '$day',
            module = '$module',
            task = '$task',
            task_type = '$taskType',
            priority = '$priority',
            expected_delivery_date = '$expectedDelivery',
            deep_description = '$deepDescription' 
            WHERE id = $taskId";

    if ($conn->query($sql) === TRUE) {
        echo "Task updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
