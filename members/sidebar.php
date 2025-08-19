<link rel="stylesheet" href="../assets/css/members/sidebarcss.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="member-sidebar">
    <div class="profile-summary">
        <h3><?= $_SESSION['name'] ?></h3>
        <p>Member since: <?= date('M Y', strtotime($_SESSION['join_date'])) ?></p>
    </div>
    <nav>
         <a href="dashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : '' ?>>
      <i class="fas fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
    <a href="profile.php" <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'class="active"' : '' ?>>
      <i class="fas fa-user"></i>
      <span>My Profile</span>
    </a>
    <a href="booking.php" <?= basename($_SERVER['PHP_SELF']) == 'booking.php' ? 'class="active"' : '' ?>>
      <i class="fas fa-calendar-check"></i>
      <span>Book Classes</span>
    </a>
    <a href="payments.php" <?= basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'class="active"' : '' ?>>
      <i class="fas fa-credit-card"></i>
      <span>Payments</span>
    </a>
    <a href="../logout.php" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i>
      <span>Logout</span>
    </a>
    </nav>
</div>