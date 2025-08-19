<?php
header('Content-Type: application/json');
include '../includes/config.php';
session_start();

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit();
}

// Check authentication
if (!isset($_SESSION['member_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

// Get and validate input
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE || !isset($input['class_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid request data']);
    exit();
}

$class_id = (int)$input['class_id'];
$member_id = (int)$_SESSION['member_id'];

// Start transaction for atomic operations
$conn->begin_transaction();

try {
    // Check capacity using prepared statement
    $stmt = $conn->prepare("SELECT max_capacity, current_bookings FROM classes WHERE class_id = ? FOR UPDATE");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $class = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$class) {
        throw new Exception("Class not found");
    }

    // Check if already booked
    $check = $conn->prepare("SELECT booking_id FROM bookings WHERE member_id = ? AND class_id = ?");
    $check->bind_param("ii", $member_id, $class_id);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        throw new Exception("You've already booked this class");
    }
    $check->close();

    if ($class['current_bookings'] >= $class['max_capacity']) {
        echo json_encode(['status' => 'waitlisted']);
        $conn->commit();
        exit();
    }

    // Book class using prepared statements
    $insert = $conn->prepare("INSERT INTO bookings (member_id, class_id, booking_date) VALUES (?, ?, NOW())");
    $insert->bind_param("ii", $member_id, $class_id);
    $insert->execute();
    $insert->close();

    // Update booking count
    $update = $conn->prepare("UPDATE classes SET current_bookings = current_bookings + 1 WHERE class_id = ?");
    $update->bind_param("i", $class_id);
    $update->execute();
    $update->close();

    $conn->commit();
    echo json_encode(['status' => 'success']);
    
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => $e->getMessage()]);
}


?>
