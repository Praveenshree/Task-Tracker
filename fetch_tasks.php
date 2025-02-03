<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task_tracker"; // Change if needed

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tasks ORDER BY expected_delivery_date ASC";
$result = $conn->query($sql);

$output = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>
                        <td>{$row['day']}</td>
                        <td>{$row['module']}</td>
                        <td>{$row['task']}</td>
                        <td>{$row['task_type']}</td>
                        <td>{$row['priority']}</td>
                        <td>{$row['expected_delivery_date']}</td>
                        <td>{$row['deep_description']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <button class='btn btn-success btn-sm submitTask' data-id='{$row['id']}'>Submit</button>
                            <button class='btn btn-warning btn-sm inProgressTask' data-id='{$row['id']}'>In-Progress</button>
                            <button class='btn btn-primary btn-sm editTask' data-id='{$row['id']}'>Edit</button>
                            <button class='btn btn-danger btn-sm deleteTask' data-id='{$row['id']}'>Delete</button>
                        </td>
                    </tr>";
    }
} else {
    $output .= "<tr><td colspan='9' class='text-center'>No tasks available</td></tr>";
}

echo $output;
$conn->close();
?>
