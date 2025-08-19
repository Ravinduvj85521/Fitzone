<?php
header('Content-Type: application/json');
include '../includes/config.php';

$class_id = (int)$_GET['class_id'];

$class = $conn->query("SELECT max_capacity, current_bookings 
                      FROM classes 
                      WHERE class_id = $class_id")->fetch_assoc();

echo json_encode([
    'available' => $class['max_capacity'] - $class['current_bookings'],
    'waitlist' => $class['current_bookings'] >= $class['max_capacity']
]);
?>