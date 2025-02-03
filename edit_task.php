<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $day = $_POST['day'];
    $module = $_POST['module'];
    $task = $_POST['task'];
    $task_type = $_POST['task_type'];
    $priority = $_POST['priority'];
    $expected_delivery_date = $_POST['expected_delivery_date'];
    $deep_description = $_POST['deep_description'];

    $sql = "UPDATE tasks SET day='$day', module='$module', task='$task', task_type='$task_type', priority='$priority',
            expected_delivery_date='$expected_delivery_date', deep_description='$deep_description' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
