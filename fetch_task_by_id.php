<?php
// fetch_task_by_id.php
include 'database_connection.php'; // Include your database connection file

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $taskId]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($task) {
        echo json_encode($task);
    } else {
        echo json_encode(['error' => 'Task not found']);
    }
}
?>
