<?php
$customCSS = "trainers.css?v=<?php echo time(); ?>  ";
include 'includes/config.php';
$trainers = $conn->query("SELECT * FROM trainers ORDER BY name");
?>
<?php include 'includes/header.php'; ?>

<section class="trainers-section">
    <h1>Our Trainers</h1>
    
    <div class="trainer-grid">
        <?php while($trainer = $trainers->fetch_assoc()): ?>
        <div class="trainer-card">
            <div class="trainer-image">
                <img src="assets/imgs/trainers/<?= $trainer['trainer_id'] ?>.jpg" alt="<?= $trainer['name'] ?>">
            </div>
            <div class="trainer-info">
                <h3><?= $trainer['name'] ?></h3>
                <p class="specialization"><?= $trainer['specialization'] ?></p>
                <p class="certification"><?= $trainer['certification'] ?></p>
                <p class="bio"><?= $trainer['bio'] ?></p>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>