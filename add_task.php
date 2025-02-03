<?php
include 'db_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $day = $_POST['day'];
    $module = $_POST['module'];
    $task = $_POST['task'];
    $task_type = $_POST['task_type'];
    $priority = $_POST['priority'];
    $expected_delivery_date = $_POST['expected_delivery_date'];
    $deep_description = $_POST['deep_description'];

    // Validate data (optional but recommended)
    if (empty($day) || empty($module) || empty($task) || empty($task_type) || empty($priority) || empty($expected_delivery_date)) {
        die("All fields are required.");
    }

    // Insert data into the database
    $sql = "INSERT INTO tasks (day, module, task, task_type, priority, expected_delivery_date, deep_description)
            VALUES ('$day', '$module', '$task', '$task_type', '$priority', '$expected_delivery_date', '$deep_description')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the main page after successful insertion
        header("Location: index.php");
        exit();
    } else {
        // Display error if insertion fails
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}
?>
