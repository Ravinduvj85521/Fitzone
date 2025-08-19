<?php 
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/config.php';
?>


<!DOCTYPE html>
<html>
<head>
    <title>My Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/members/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="member-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="main-content">
            <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
            
            <div class="upcoming-classes">
                <h2>Your Upcoming Classes</h2>
                <?php
                $stmt = $conn->prepare("SELECT c.class_id, c.class_name, c.start_time, t.name as trainer_name 
                                       FROM bookings b
                                       JOIN classes c ON b.class_id = c.class_id
                                       JOIN trainers t ON c.trainer_id = t.trainer_id
                                       WHERE b.member_id = ? AND c.start_time > NOW()
                                       ORDER BY c.start_time");
                $stmt->bind_param("i", $_SESSION['member_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    echo '<div class="class-grid">';
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="class-card">';
                        echo '<h3>' . htmlspecialchars($row['class_name']) . '</h3>';
                        echo '<p><strong>Trainer:</strong> ' . htmlspecialchars($row['trainer_name']) . '</p>';
                        echo '<p><strong>Time:</strong> ' . date('D, M j g:i A', strtotime($row['start_time'])) . '</p>';
                        echo '<form method="POST" action="cancel_booking.php" class="cancel-form">';
                        echo '<input type="hidden" name="class_id" value="' . $row['class_id'] . '">';
                        echo '<button type="submit" class="cancel-btn">Cancel Booking</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="no-classes">';
                    echo '<p>You have no upcoming classes. <a href="booking.php" class="book-link">Book a class now!</a></p>';
                    echo '</div>';
                }
                ?>
            </div>
            
            <div class="recent-bookings">
                <h2>Recent Bookings</h2>
                <?php
                $stmt = $conn->prepare("SELECT c.class_name, c.start_time, b.booking_date 
                                       FROM bookings b
                                       JOIN classes c ON b.class_id = c.class_id
                                       WHERE b.member_id = ?
                                       ORDER BY b.booking_date DESC LIMIT 5");
                $stmt->bind_param("i", $_SESSION['member_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    echo '<table class="bookings-table">';
                    echo '<thead><tr><th>Class</th><th>Date</th><th>Booked On</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['class_name']) . '</td>';
                        echo '<td>' . date('M j, Y g:i A', strtotime($row['start_time'])) . '</td>';
                        echo '<td>' . date('M j, Y', strtotime($row['booking_date'])) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>No booking history found.</p>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>