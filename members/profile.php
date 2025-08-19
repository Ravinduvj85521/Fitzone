<?php
include '../includes/config.php';
include '../includes/auth.php';
redirectIfNotLoggedIn();

$member_id = $_SESSION['member_id'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    
    $stmt = $conn->prepare("UPDATE members 
                          SET name = ?, email = ?, phone = ? 
                          WHERE member_id = ?");
    $stmt->bind_param("sssi", $name, $email, $phone, $member_id);
    $stmt->execute();
    
    $_SESSION['name'] = $name;
    $success = "Profile updated successfully";
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    
    $member = $conn->query("SELECT password FROM members WHERE member_id = $member_id")->fetch_assoc();
    
    if (password_verify($current, $member['password'])) {
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        $conn->query("UPDATE members SET password = '$new_hash' WHERE member_id = $member_id");
        $success = "Password changed successfully";
    } else {
        $error = "Current password is incorrect";
    }
}

$member = $conn->query("SELECT * FROM members WHERE member_id = $member_id")->fetch_assoc();
?>
<!-- php include '../includes/header.php'; -->

<div class="member-container">
    <?php include 'sidebar.php'; ?>
    <link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/members/profilecss.css">

    <main class="member-content">
        <h1>My Profile</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        
        <section class="profile-section">
            <h2>Personal Information</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?= $member['name'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= $member['email'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" value="<?= $member['phone'] ?>">
                </div>
                
                <button type="submit" class="btn">Update Profile</button>
            </form>
        </section>
        
        <section class="password-section">
            <h2>Change Password</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>
                
                <button type="submit" name="change_password" class="btn">Change Password</button>
            </form>
        </section>
    </main>
</div>

<!-- php include '../includes/footer.php';  -->