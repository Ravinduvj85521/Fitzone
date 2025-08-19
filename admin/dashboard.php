<?php
include '../includes/config.php';
include '../includes/auth.php';

// Admin authorization check
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get stats for dashboard
$members = $conn->query("SELECT COUNT(*) as count FROM members")->fetch_assoc();
$classes = $conn->query("SELECT COUNT(*) as count FROM classes")->fetch_assoc();
$bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc();
?>
<!-- php include '../includes/header.php'; -->
<!-- <link rel="stylesheet" href="../assets/css/style.css"> -->
<link rel="stylesheet" href="../assets/css/admin/dashboard.css?=<?= time() ?>">

<div class="admin-container">
    <?php include 'sidebar.php'; ?>

    <main class="admin-content">
        <h1>Admin Dashboard</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Members</h3>
                <p><?= $members['count'] ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Classes</h3>
                <p><?= $classes['count'] ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p><?= $bookings['count'] ?></p>
            </div>
        </div>

        <section class="recent-activity">
            <h2>Recent Bookings</h2>
            <?php
            $recent = $conn->query("SELECT m.name, c.class_name, b.booking_date 
                                   FROM bookings b
                                   JOIN members m ON b.member_id = m.member_id
                                   JOIN classes c ON b.class_id = c.class_id
                                   ORDER BY b.booking_date DESC LIMIT 5");
            
            if ($recent->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Class</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $recent->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['class_name'] ?></td>
                            <td><?= date('M j, Y g:i A', strtotime($row['booking_date'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No recent bookings found</p>
            <?php endif; ?>
        </section>
    </main>
</div>

<!-- php include '../includes/footer.php'; -->