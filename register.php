<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/config.php';
include 'includes/functions.php';

// Initialize variables
$errors = [];
$formData = [
    'name' => '',
    'email' => '',
    'phone' => ''
];

// Redirect if already logged in
if (isset($_SESSION['member_id'])) {
    header("Location: members/dashboard.php");
    exit();
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize form data
    $formData['name'] = sanitize($_POST['name'] ?? '');
    $formData['email'] = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $formData['phone'] = sanitize($_POST['phone'] ?? '');
    $membership = isset($_GET['plan']) ? sanitize($_GET['plan']) : 'basic';
    
    // Validate inputs
    if (empty($formData['name'])) {
        $errors['name'] = "Name is required";
    } elseif (strlen($formData['name']) < 2) {
        $errors['name'] = "Name must be at least 2 characters";
    }

    if (empty($formData['email'])) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT member_id FROM members WHERE email = ?");
        $stmt->bind_param("s", $formData['email']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors['email'] = "Email already registered";
        }
    }
    
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $errors['password'] = "Password must contain at least one uppercase letter";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $errors['password'] = "Password must contain at least one lowercase letter";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $errors['password'] = "Password must contain at least one number";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }
    
    if (!empty($formData['phone']) && !preg_match("/^[0-9]{10,15}$/", $formData['phone'])) {
        $errors['phone'] = "Invalid phone number format";
    }
    
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new member
        $stmt = $conn->prepare("INSERT INTO members (name, email, password, phone, membership_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $formData['name'], $formData['email'], $hashed_password, $formData['phone'], $membership);
        
        if ($stmt->execute()) {
            // Send welcome email
            $subject = "Welcome to FitZone Fitness Center!";
            $message = "Hi {$formData['name']},<br><br>Thank you for registering with FitZone. Your $membership membership is now active.<br><br>Start your fitness journey today!";
            sendNotification($formData['email'], $subject, $message);
            
            // Redirect to login
            header("Location: login.php?registered=1");
            exit();
        } else {
            $error = "Registration failed. Please try again. Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FitZone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
            border-radius: 0.25rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-text {
            color: #6c757d;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
<div class="auth-container">
    <div class="auth-form">
        <h1>Create Account</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert error">
                Please fix the <?= count($errors) ?> error(s) below to complete your registration.
            </div>
        <?php endif; ?>
        
        <form method="POST" id="registerForm">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($formData['name']) ?>" required>
                <?php if (!empty($errors['name'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['name']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($formData['email']) ?>" required>
                <?php if (!empty($errors['email'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['email']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($formData['phone']) ?>">
                <?php if (!empty($errors['phone'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['phone']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <?php if (!empty($errors['password'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['password']) ?></span>
                <?php endif; ?>
                <small class="form-text">Must be at least 8 characters with uppercase, lowercase, and number</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <?php if (!empty($errors['confirm_password'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['confirm_password']) ?></span>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_GET['plan'])): ?>
                <div class="membership-notice">
                    <p>You're signing up for our <strong><?= htmlspecialchars(ucfirst($_GET['plan'])) ?></strong> plan</p>
                </div>
            <?php endif; ?>
            
            <div class="form-footer">
                <button type="submit" class="btn">Register</button>
                <p>Already have an account? <a href="login.php">Login here</a></p>
                <p>By registering, you agree to our <a href="terms.php">Terms of Service</a></p>
            </div>
        </form>
    </div>
    
    <div class="auth-image">
        <img src="assets/images/register-bg.jpg" alt="Join Our Community">
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;
    
    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    
    // Name validation
    const name = document.getElementById('name');
    if (name.value.trim() === '') {
        name.nextElementSibling.textContent = 'Name is required';
        valid = false;
    } else if (name.value.trim().length < 2) {
        name.nextElementSibling.textContent = 'Name must be at least 2 characters';
        valid = false;
    }
    
    // Email validation
    const email = document.getElementById('email');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        email.nextElementSibling.textContent = 'Please enter a valid email';
        valid = false;
    }
    
    // Password validation
    const password = document.getElementById('password');
    if (password.value.length < 8) {
        password.nextElementSibling.textContent = 'Password must be at least 8 characters';
        valid = false;
    } else if (!/[A-Z]/.test(password.value)) {
        password.nextElementSibling.textContent = 'Password must contain at least one uppercase letter';
        valid = false;
    } else if (!/[a-z]/.test(password.value)) {
        password.nextElementSibling.textContent = 'Password must contain at least one lowercase letter';
        valid = false;
    } else if (!/[0-9]/.test(password.value)) {
        password.nextElementSibling.textContent = 'Password must contain at least one number';
        valid = false;
    }
    
    // Confirm password validation
    const confirmPassword = document.getElementById('confirm_password');
    if (password.value !== confirmPassword.value) {
        confirmPassword.nextElementSibling.textContent = 'Passwords do not match';
        valid = false;
    }
    
    if (!valid) {
        e.preventDefault();
        // Show general error alert if not already visible
        if (!document.querySelector('.alert.error')) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert error';
            alertDiv.textContent = 'Please fix the errors below to complete your registration.';
            document.querySelector('.auth-form').insertBefore(alertDiv, document.querySelector('form'));
        }
    }
});
</script>

</body>
</html>