<?php
header('Content-Type: application/json');
include '../includes/config.php';

$type = $_GET['type'] ?? 'all';
$date = $_GET['date'] ?? date('Y-m-d');

$query = "SELECT c.*, t.name as trainer_name 
          FROM classes c 
          JOIN trainers t ON c.trainer_id = t.trainer_id
          WHERE DATE(c.start_time) = ?";

if ($type != 'all') {
    $query .= " AND c.class_type = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $date, $type);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $date);
}

$stmt->execute();
$result = $stmt->get_result();
$classes = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($classes);
?>