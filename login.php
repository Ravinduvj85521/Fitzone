<?php
session_start();
include 'includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['member_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: members/dashboard.php");
    }
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Better than custom sanitize()
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields";
        header("Location: login.php"); // Redirect back to login
        exit();
    } else {
        // Check if user exists
        $sql = "SELECT member_id, name, password, membership_type, join_date, role FROM members WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            die("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['member_id'] = $user['member_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['membership'] = $user['membership_type'];
                $_SESSION['join_date'] = $user['join_date'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect to appropriate dashboard
                if ($user['role'] == 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: members/dashboard.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Invalid email or password";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "No account found with that email";
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!-- HTML Form (Updated to use $_SESSION['error']) -->
<?php include 'includes/header.php'; ?>

<div class="auth-container">
    <div class="auth-form">
        <h1>Member Login</h1>
        
        <?php if (isset($_GET['registered'])): ?>
            <div class="alert success">
                Registration successful! Please login.
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['logout'])): ?>
            <div class="alert info">
                You have been logged out successfully.
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); // Clear error after displaying ?>
        <?php endif; ?>
        
        <form method="POST" id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required>
                <span class="error-message" id="emailError"></span>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <span class="error-message" id="passwordError"></span>
            </div>
            
            <div class="form-footer">
                <button type="submit" class="btn">Login</button>
                <p>Don't have an account? <a href="register.php">Register here</a></p>
                <p><a href="forgot-password.php">Forgot your password?</a></p>
            </div>
        </form>
    </div>
    
    <div class="auth-image">
        <img src="assets/images/login-bg.jpg" alt="Fitness Motivation">
    </div>
</div>

<!-- Remove or modify JavaScript validation if it's blocking submission -->
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    // Clear previous errors
    document.getElementById('emailError').textContent = '';
    document.getElementById('passwordError').textContent = '';
    
    // Proceed with form submission (let PHP handle validation)
    return true;
});
</script>

<?php include 'includes/footer.php'; ?>