<link rel="stylesheet" href="../assets/css/admin/sidebar.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Mobile Toggle Button (place right after opening <body> tag in your layout) -->
<button class="sidebar-toggle">
    <i class="fas fa-bars"></i>
</button>
<div class="sidebar-overlay"></div>

<!-- Sidebar Structure -->
<div class="admin-sidebar">
    <h3><i class="fas fa-cog"></i> Admin Panel</h3>
    <nav>
        <a href="dashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : '' ?>>
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="members.php" <?= basename($_SERVER['PHP_SELF']) == 'members.php' ? 'class="active"' : '' ?>>
            <i class="fas fa-users"></i>
            <span>Members</span>
        </a>
        <a href="classes.php" <?= basename($_SERVER['PHP_SELF']) == 'classes.php' ? 'class="active"' : '' ?>>
            <i class="fas fa-dumbbell"></i>
            <span>Classes</span>
        </a>
        <a href="trainers.php" <?= basename($_SERVER['PHP_SELF']) == 'trainers.php' ? 'class="active"' : '' ?>>
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Trainers</span>
        </a>
        <a href="../logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>
</div>

<!-- Add this JavaScript before </body> -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.admin-sidebar');
    const toggleBtn = document.querySelector('.sidebar-toggle');
    const overlay = document.querySelector('.sidebar-overlay');
    
    // Toggle sidebar
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none';
    });
    
    // Close sidebar when clicking overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.style.display = 'none';
    });
    
    // Auto-collapse on smaller screens
    function checkScreenSize() {
        if (window.innerWidth <= 992) {
            sidebar.classList.remove('active');
            overlay.style.display = 'none';
        }
    }
    
    window.addEventListener('resize', checkScreenSize);
    checkScreenSize();
});
</script>