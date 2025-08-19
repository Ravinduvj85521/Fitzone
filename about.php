<?php
// MUST BE THE VERY FIRST LINE - Load configuration
require __DIR__ . '/includes/config.php';

// Set page variables
$pageTitle = "About Us";
$customCSS = "about.css?v=<?php echo time(); ?>";

// Fetch team members from database
$teamMembers = [];
try {
    $stmt = $conn->prepare("SELECT * FROM trainers ORDER BY name LIMIT 4");
    $stmt->execute();
    $result = $stmt->get_result();
    $teamMembers = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching team members: " . $e->getMessage());
    // Continue with empty array if there's an error
}

// Include header after setting variables
require __DIR__ . '/includes/header.php';
?>

<section class="about-section">
    <h1>About FitZone</h1>
    
    <div class="about-content">
        <div class="about-text">
            <h2>Our Story</h2>
            <p>Founded in 2023 in Kurunegala, FitZone was born from a passion for helping our community achieve their fitness goals in a welcoming, state-of-the-art facility.</p>
            
            <h2>Our Facility</h2>
            <ul class="facility-features">
                <li><i class="fas fa-check-circle"></i> 2000 sq ft of workout space</li>
                <li><i class="fas fa-check-circle"></i> Cardio zone with 20+ machines</li>
                <li><i class="fas fa-check-circle"></i> Free weights area</li>
                <li><i class="fas fa-check-circle"></i> Group exercise studio</li>
                <li><i class="fas fa-check-circle"></i> Locker rooms with showers</li>
            </ul>
        </div>
        
        <div class="about-image">
            <img src="assets/imgs/gym-interior.jpg" alt="FitZone Facility" loading="lazy">
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number" data-count="2000">0</div>
            <div class="stat-label">Square Feet</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-count="50">0</div>
            <div class="stat-label">Equipment Pieces</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-count="15">0</div>
            <div class="stat-label">Weekly Classes</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-count="500">0</div>
            <div class="stat-label">Happy Members</div>
        </div>
    </div>
</section>

<section class="team-section">
    <h2>Meet Our Team</h2>
    
    <div class="team-grid">
        <?php if (!empty($teamMembers)): ?>
            <?php foreach ($teamMembers as $member): ?>
                <div class="team-member">
                    <img src="assets/imgs/trainers/<?= htmlspecialchars($member['trainer_id']) ?>.jpg" 
                         alt="<?= htmlspecialchars($member['name']) ?>" 
                         loading="lazy">
                    <div class="team-info">
                        <h3><?= htmlspecialchars($member['name']) ?></h3>
                        <p class="specialization"><?= htmlspecialchars($member['specialization']) ?></p>
                        <div class="team-social">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-team">Our talented team profiles are coming soon!</p>
        <?php endif; ?>
    </div>
</section>

<?php
// Include footer
require __DIR__ . '/includes/footer.php';
?>

<script>
// Animate stats counters
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200;
    
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-count');
        const count = +counter.innerText;
        const increment = target / speed;
        
        if (count < target) {
            counter.innerText = Math.ceil(count + increment);
            setTimeout(arguments.callee, 1);
        } else {
            counter.innerText = target;
        }
    });
});
</script>