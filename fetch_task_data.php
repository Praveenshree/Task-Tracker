<?php
// Include your database connection
include('db_connection.php');

$query = "SELECT 
            COUNT(CASE WHEN status = 'Pending' THEN 1 END) AS pending,
            COUNT(CASE WHEN status = 'In-Progress' THEN 1 END) AS inProgress,
            COUNT(CASE WHEN status = 'Completed' THEN 1 END) AS completed,
            COUNT(CASE WHEN priority = 'High' THEN 1 END) AS highPriority,
            COUNT(CASE WHEN priority = 'Medium' THEN 1 END) AS mediumPriority,
            COUNT(CASE WHEN priority = 'Low' THEN 1 END) AS lowPriority,
            COUNT(CASE WHEN task_type = 'Bug' THEN 1 END) AS bug,
            COUNT(CASE WHEN task_type = 'Enhancement' THEN 1 END) AS enhancement,
            COUNT(CASE WHEN task_type = 'Feature' THEN 1 END) AS feature
          FROM tasks";

$result = mysqli_query($conn, $query);

$data = mysqli_fetch_assoc($result);

echo json_encode($data);
?>
