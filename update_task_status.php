<?php
require 'db_connection.php'; // Ensure database connection is included

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = [];

    if (!isset($_POST['id'])) {
        echo json_encode(["error" => "Invalid request: Task ID is missing."]);
        exit;
    }

    $taskId = intval($_POST['id']);

    // Check if task exists
    $checkSql = "SELECT id FROM tasks WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $taskId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Task not found!"]);
        exit;
    }

    // Determine the type of update (status or details)
    if (isset($_POST['status'])) {
        // Update status and remarks
        $status = htmlspecialchars(trim($_POST['status']), ENT_QUOTES, 'UTF-8');
        $remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';

        $sql = "UPDATE tasks SET status = ?, remarks = NULLIF(?, '') WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $status, $remarks, $taskId);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Task status updated successfully!"]);
        } else {
            echo json_encode(["error" => "Error updating task status: " . $conn->error]);
        }
    } elseif (isset($_POST['title'], $_POST['description'], $_POST['due_date'])) {
        // Update task details
        $title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
        $dueDate = $_POST['due_date'];

        $sql = "UPDATE tasks SET title = ?, description = ?, due_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $dueDate, $taskId);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Task updated successfully!"]);
        } else {
            echo json_encode(["error" => "Error updating task: " . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Invalid request: Missing required fields."]);
    }
} else {
    echo json_encode(["error" => "Invalid request method!"]);
}

?>
