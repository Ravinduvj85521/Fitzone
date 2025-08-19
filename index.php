<?php
// MUST BE THE VERY FIRST LINE - Load configuration
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php'; 

// Set page variables
$pageTitle = "FitZone Fitness Center";
$customCSS = "style.css"; // Your main CSS file

// Include header after setting variables
require __DIR__ . '/includes/header.php';

// Fetch featured classes from database
$featuredClasses = [];
try {
    $stmt = $conn->prepare("SELECT class_id, class_name, class_type, start_time FROM classes WHERE start_time > NOW() ORDER BY start_time LIMIT 3");
    $stmt->execute();
    $result = $stmt->get_result();
    $featuredClasses = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching classes: " . $e->getMessage());
    // Continue with empty array if there's an error
}
?>

<!-- Hero Section -->
<section class="hero">
    <video autoplay muted loop playsinline>
        <source src="assets/video.mp4" type="video/mp4">
        <!-- Fallback image if video doesn't load -->
        <!-- <img src="../images/hero-bg.jpg" alt="Fallback hero image"> -->
    </video>
    
    <div class="hero-content">
        <h1>Transform Your Body, Transform Your Life</h1>
        <p>Join Kurunegala's premier fitness destination</p>
        <a href="register.php" class="cta-button">Start Your Journey Today</a>
    </div>
</section>

<!-- Featured Classes -->
<section class="featured-classes">
    <h2>Popular Classes</h2>
    <div class="class-grid">
        <?php if (!empty($featuredClasses)): ?>
            <?php foreach ($featuredClasses as $class): ?>
                <div class="class-card">
                    <h3><?= htmlspecialchars($class['class_name']) ?></h3>
                    <p class="class-type"><?= ucfirst(htmlspecialchars($class['class_type'])) ?></p>
                    <p class="class-time">
                        <?= date('D, M j g:i A', strtotime($class['start_time'])) ?>
                    </p>
                    <?php if (isLoggedIn()): ?>
                        <a href="members/booking.php?class_id=<?= $class['class_id'] ?>" class="book-btn">Book Now</a>
                    <?php else: ?>
                        <a href="login.php" class="book-btn">Login to Book</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-classes">No upcoming classes found. Check back later!</p>
        <?php endif; ?>
    </div>
</section>

<?php
// Include footer
require __DIR__ . '/includes/footer.php';
?>