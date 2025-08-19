<?php
include '../includes/config.php';
include '../includes/auth.php';
redirectIfNotLoggedIn();

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_id'])) {
    $class_id = intval($_POST['class_id']);
    $member_id = $_SESSION['member_id'];
    
    // Check if class exists and has available spots
    $class_check = $conn->prepare("SELECT max_capacity, current_bookings FROM classes WHERE class_id = ?");
    $class_check->bind_param("i", $class_id);
    $class_check->execute();
    $class_result = $class_check->get_result();
    
    if ($class_result->num_rows > 0) {
        $class = $class_result->fetch_assoc();
        if ($class['current_bookings'] < $class['max_capacity']) {
            // Check if user already booked this class
            $booking_check = $conn->prepare("SELECT booking_id FROM bookings WHERE member_id = ? AND class_id = ?");
            $booking_check->bind_param("ii", $member_id, $class_id);
            $booking_check->execute();
            
            if ($booking_check->get_result()->num_rows === 0) {
                // Start transaction
                $conn->begin_transaction();
                
                try {
                    // Insert booking
                    $insert = $conn->prepare("INSERT INTO bookings (member_id, class_id, booking_date) VALUES (?, ?, NOW())");
                    $insert->bind_param("ii", $member_id, $class_id);
                    $insert->execute();
                    
                    // Update class bookings count
                    $update = $conn->prepare("UPDATE classes SET current_bookings = current_bookings + 1 WHERE class_id = ?");
                    $update->bind_param("i", $class_id);
                    $update->execute();
                    
                    $conn->commit();
                    $_SESSION['success'] = "Class booked successfully!";
                } catch (Exception $e) {
                    $conn->rollback();
                    $_SESSION['error'] = "Error booking class: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "You've already booked this class!";
            }
        } else {
            $_SESSION['error'] = "This class is already full!";
        }
    } else {
        $_SESSION['error'] = "Class not found!";
    }
    
    header("Location: booking.php");
    exit();
}

// Get available classes
$classes = $conn->query("SELECT c.*, t.name as trainer_name 
                        FROM classes c 
                        JOIN trainers t ON c.trainer_id = t.trainer_id
                        WHERE c.start_time > NOW()
                        ORDER BY c.start_time");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Class</title>
    <link rel="stylesheet" href="../assets/css/booking.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="member-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="member-content">
            <h1>Book a Class</h1>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <div class="class-grid">
                <?php while($class = $classes->fetch_assoc()): ?>
                <div class="class-card">
                    <h3><?= htmlspecialchars($class['class_name']) ?></h3>
                    <p><strong>Trainer:</strong> <?= htmlspecialchars($class['trainer_name']) ?></p>
                    <p><strong>Time:</strong> <?= date('D, M j g:i A', strtotime($class['start_time'])) ?></p>
                    <p><strong>Spots:</strong> <?= $class['max_capacity'] - $class['current_bookings'] ?> remaining</p>
                    <form method="POST" class="booking-form">
                        <input type="hidden" name="class_id" value="<?= $class['class_id'] ?>">
                        <button type="submit" class="book-class">Book Now</button>
                    </form>
                </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>

    <!-- <script src="../assets/js/booking.js"></script> -->
</body>
</html>