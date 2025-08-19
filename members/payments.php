<?php
include '../includes/config.php';
include '../includes/auth.php';
redirectIfNotLoggedIn();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$member_id = $_SESSION['member_id'];

// Handle new payment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['payment_error'] = "Security token mismatch. Please try again.";
        header("Location: payments.php");
        exit();
    }

    try {
        $amount = (float)$_POST['amount'];
        $method = sanitize($_POST['payment_method']);
        
        // Validate amount
        if ($amount <= 0) {
            throw new Exception("Amount must be greater than 0");
        }

        $stmt = $conn->prepare("INSERT INTO payments 
                              (member_id, amount, payment_method) 
                              VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $member_id, $amount, $method);
        
        if ($stmt->execute()) {
            // Update membership type if payment is for membership
            if (isset($_POST['membership_type'])) {
                $type = sanitize($_POST['membership_type']);
                $conn->query("UPDATE members SET membership_type = '$type' WHERE member_id = $member_id");
                $_SESSION['membership_updated'] = $type;
            }
            
            $_SESSION['payment_success'] = "Payment of Rs. " . number_format($amount, 2) . " processed successfully";
        } else {
            throw new Exception("Database error: " . $conn->error);
        }
    } catch (Exception $e) {
        $_SESSION['payment_error'] = "Error: " . $e->getMessage();
    }
    
    header("Location: payments.php");
    exit();
}

// Get messages from session
$success = $_SESSION['payment_success'] ?? null;
$error = $_SESSION['payment_error'] ?? null;
$membership_updated = $_SESSION['membership_updated'] ?? null;

// Clear messages from session
unset($_SESSION['payment_success'], $_SESSION['payment_error'], $_SESSION['membership_updated']);

// Get payment history
$payments = $conn->query("SELECT * FROM payments 
                         WHERE member_id = $member_id 
                         ORDER BY payment_date DESC");
?>
<!-- php include '../includes/header.php';  -->
<link rel="stylesheet" href="../assets/css/members/payments.css">
<link rel="stylesheet" href="../assets/css/style.css">

<div class="member-container">
    <?php include 'sidebar.php'; ?>
    
    <main class="member-content">
        <h1>My Payments</h1>
        
        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($membership_updated): ?>
            <div class="alert info">
                Membership updated to <?= ucfirst($membership_updated) ?> successfully
            </div>
        <?php endif; ?>
        
        <section class="new-payment">
            <h2>Make a Payment</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="form-group">
                    <label>Payment For</label>
                    <select name="membership_type" class="form-control">
                        <option value="">Select Membership</option>
                        <option value="basic" <?= ($membership_updated ?? '') == 'basic' ? 'selected' : '' ?>>Basic Membership (Rs. 3000/month)</option>
                        <option value="premium" <?= ($membership_updated ?? '') == 'premium' ? 'selected' : '' ?>>Premium Membership (Rs. 5000/month)</option>
                        <option value="vip" <?= ($membership_updated ?? '') == 'vip' ? 'selected' : '' ?>>VIP Membership (Rs. 8000/month)</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Amount (LKR)</label>
                    <input type="number" name="amount" step="0.01" min="0" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="cash">Cash</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="digital">Digital Wallet</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit Payment</button>
            </form>
        </section>
        
        <section class="payment-history">
            <h2>Payment History</h2>
            <?php if ($payments->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="member-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($payment = $payments->fetch_assoc()): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($payment['payment_date'])) ?></td>
                                <td>Rs. <?= number_format($payment['amount'], 2) ?></td>
                                <td><?= ucfirst($payment['payment_method']) ?></td>
                                <td><span class="status completed">Completed</span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-payments">No payment history found.</p>
            <?php endif; ?>
        </section>
    </main>
</div>

<!-- php include '../includes/footer.php';  -->