<?php
$customCSS = "contact.css?v=<?php echo time(); ?>";
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $message = sanitize($_POST['message']);

    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    $stmt->execute();

    $success = "Your message has been sent! We'll respond within 24 hours.";
}
?>
<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<section class="contact-section">
    <h1>Contact Us</h1>

    <div class="contact-container">
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <p><i class="fas fa-map-marker-alt"></i><strong>Address:</strong> 123 Fitness Street, Kurunegala</p>
            <p><i class="fas fa-phone"></i><strong>Phone:</strong> +94 76 123 4567</p>
            <p><i class="fas fa-envelope"></i><strong>Email:</strong> info@fitzone.lk</p>
            <p><i class="fas fa-clock"></i><strong>Hours:</strong> Mon-Fri: 6AM-10PM, Sat-Sun: 8AM-8PM</p>
        </div>


        <div class="contact-form">
            <h2>Send Us a Message</h2>
            <?php if (isset($success)): ?>
                <div class="alert success"><?= $success ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </div>

    <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.715635603618!2d80.3658604147746!3d7.268394394753976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae3158b9d8d3b19%3A0x4a5a1a1a1a1a1a1a!2sKurunegala!5e0!3m2!1sen!2slk!4v1620000000000!5m2!1sen!2slk" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</section>

<?php include 'includes/footer.php'; ?>