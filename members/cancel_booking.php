<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_id'])) {
    $class_id = intval($_POST['class_id']);
    $member_id = $_SESSION['member_id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete booking
        $delete = $conn->prepare("DELETE FROM bookings WHERE member_id = ? AND class_id = ?");
        $delete->bind_param("ii", $member_id, $class_id);
        $delete->execute();
        
        // Update class bookings count
        $update = $conn->prepare("UPDATE classes SET current_bookings = current_bookings - 1 WHERE class_id = ?");
        $update->bind_param("i", $class_id);
        $update->execute();
        
        $conn->commit();
        $_SESSION['success'] = "Booking cancelled successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error cancelling booking: " . $e->getMessage();
    }
    
    header("Location: dashboard.php");
    exit();
}

header("Location: dashboard.php");
exit();
?>