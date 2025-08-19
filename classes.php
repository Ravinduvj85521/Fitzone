<?php
// MUST BE THE VERY FIRST LINE - Load configuration
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php'; 


// Set page variables
$pageTitle = "Our Classes";
$customCSS = "classes.css?v=<?php echo time(); ?>";

// Fetch classes from database with error handling
$classes = [];
try {
    $stmt = $conn->prepare("SELECT c.*, t.name as trainer_name 
                          FROM classes c 
                          JOIN trainers t ON c.trainer_id = t.trainer_id
                          WHERE c.start_time > NOW()
                          ORDER BY c.start_time");
    $stmt->execute();
    $result = $stmt->get_result();
    $classes = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching classes: " . $e->getMessage());
    // Continue with empty array if there's an error
}

// Include header after setting variables
require __DIR__ . '/includes/header.php';
?>

<section class="classes-section">
    <h1>Our Classes</h1>
    
    <!-- <div class="class-filters">
        <select id="class-type" aria-label="Filter by class type">
            <option value="all">All Classes</option>
            <option value="yoga">Yoga</option>
            <option value="cardio">Cardio</option>
            <option value="strength">Strength Training</option>
            <option value="hiit">HIIT</option>
        </select>
        <input type="date" id="class-date" aria-label="Filter by date" value="<?= date('Y-m-d') ?>">
    </div> -->
    
    <div class="class-grid" id="class-results">
        <?php if (!empty($classes)): ?>
            <?php foreach ($classes as $class): ?>
                <div class="class-card" data-type="<?= htmlspecialchars($class['class_type']) ?>">
                    <h3><?= htmlspecialchars($class['class_name']) ?></h3>
                    <p class="trainer">With <?= htmlspecialchars($class['trainer_name']) ?></p>
                    <p class="time"><?= date('D, M j g:i A', strtotime($class['start_time'])) ?></p>
                    <p class="spots">
                        <?= max(0, (int)$class['max_capacity'] - (int)$class['current_bookings']) ?> spots left
                    </p>
                    <?php if (isLoggedIn()): ?>
                        <button class="book-btn" 
                                data-class-id="<?= (int)$class['class_id'] ?>" 
                                aria-label="Book <?= htmlspecialchars($class['class_name']) ?>">
                            Book Now
                        </button>
                    <?php else: ?>
                        <a href="login.php?redirect=classes.php" class="book-btn" 
                           aria-label="Login to book <?= htmlspecialchars($class['class_name']) ?>">
                            Login to Book
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-classes">
                <p>No upcoming classes found. Please check back later!</p>
                <?php if (isAdmin()): ?>
                    <a href="admin/classes.php" class="admin-link">Add New Classes</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
// Class filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const classTypeFilter = document.getElementById('class-type');
    const classDateFilter = document.getElementById('class-date');
    const classCards = document.querySelectorAll('.class-card');
    
    function filterClasses() {
        const selectedType = classTypeFilter.value;
        const selectedDate = classDateFilter.value;
        
        classCards.forEach(card => {
            const cardType = card.getAttribute('data-type');
            const cardDate = card.querySelector('.time').textContent;
            const matchesType = selectedType === 'all' || cardType === selectedType;
            const matchesDate = selectedDate === '' || 
                              cardDate.includes(new Date(selectedDate).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' }));
            
            card.style.display = matchesType && matchesDate ? 'block' : 'none';
        });
    }
    
    classTypeFilter.addEventListener('change', filterClasses);
    classDateFilter.addEventListener('change', filterClasses);
    
    // Booking functionality
    document.querySelectorAll('.book-btn[data-class-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const classId = this.getAttribute('data-class-id');
            fetch('api/book_class.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ class_id: classId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Class booked successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'Booking failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
});
</script>

<?php
// Include footer
require __DIR__ . '/includes/footer.php';
?>