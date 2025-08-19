<?php
if (!defined('FITZONE_CONFIG_LOADED')) {
    die('Configuration not loaded. Include config.php first.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone Fitness Center<?php echo isset($pageTitle) ? " | $pageTitle" : ""; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <?php if (isset($customCSS)): ?>
        <link rel="stylesheet" href="assets/css/<?php echo $customCSS; ?>">
    <?php endif; ?>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="contact-info">
                <span><i class="fas fa-phone"></i> +94 76 123 4567</span>
                <span><i class="fas fa-envelope"></i> info@fitzone.lk</span>
            </div>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/imgs/logo.png" alt="FitZone Logo">
                    <!-- <span>FitZone</span> -->
                </a>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Home</a></li>
                    <li><a href="about.php" <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'class="active"' : ''; ?>>About</a></li>
                    <li><a href="classes.php" <?php echo basename($_SERVER['PHP_SELF']) == 'classes.php' ? 'class="active"' : ''; ?>>Classes</a></li>
                    <li><a href="trainers.php" <?php echo basename($_SERVER['PHP_SELF']) == 'trainers.php' ? 'class="active"' : ''; ?>>Trainers</a></li>
                    <li><a href="membership.php" <?php echo basename($_SERVER['PHP_SELF']) == 'membership.php' ? 'class="active"' : ''; ?>>Membership</a></li>
                    <li><a href="blog.php" <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'class="active"' : ''; ?>>Blog</a></li>
                    <li><a href="contact.php" <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'class="active"' : ''; ?>>Contact</a></li>
                </ul>
            </nav>
            
            <div class="auth-buttons">
                <?php if (isset($_SESSION['member_id'])): ?>
                    <a href="members/dashboard.php" class="btn btn-primary">
                        <i class="fas fa-user"></i> Dashboard
                    </a>
                    <a href="logout.php" class="btn btn-outline">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                <?php endif; ?>
            </div>
            
            <button class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="classes.php">Classes</a></li>
                <li><a href="trainers.php">Trainers</a></li>
                <li><a href="membership.php">Membership</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['member_id'])): ?>
                    <li><a href="members/dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <main>
        <script>
            /* ===== Scrolled Header Effect ===== */
window.addEventListener('scroll', function() {
  const header = document.querySelector('.main-header');
  if (window.scrollY > 50) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

/* ===== Mobile Menu Toggle ===== */
document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
  document.querySelector('.mobile-menu').classList.toggle('active');
  document.querySelector('.mobile-menu-overlay').classList.toggle('active');
  document.body.classList.toggle('no-scroll');
});

document.querySelector('.mobile-menu-overlay').addEventListener('click', function() {
  document.querySelector('.mobile-menu').classList.remove('active');
  this.classList.remove('active');
  document.body.classList.remove('no-scroll');
});

        </script>