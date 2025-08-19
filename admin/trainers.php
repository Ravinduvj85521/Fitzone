<?php
include '../includes/config.php';
include '../includes/auth.php';
redirectIfNotAdmin();

// Handle trainer addition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $specialization = sanitize($_POST['specialization']);
    $bio = sanitize($_POST['bio']);
    $certification = sanitize($_POST['certification']);

    $stmt = $conn->prepare("INSERT INTO trainers 
                          (name, specialization, bio, certification) 
                          VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $specialization, $bio, $certification);
    $stmt->execute();
}

// Handle trainer deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM trainers WHERE trainer_id = $id");
    header("Location: trainers.php?deleted=1");
}

$trainers = $conn->query("SELECT * FROM trainers ORDER BY name");
?>
<!-- php include '../includes/header.php'; -->
<link rel="stylesheet" href="../assets/css/admin/trainers.css?v=1">

<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    
    <main class="admin-content">
        <h1>Trainer Management</h1>
        
        <section class="add-trainer">
            <h2>Add New Trainer</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" required>
                </div>
                
                <div class="form-group">
                    <label>Certification</label>
                    <input type="text" name="certification" required>
                </div>
                
                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio" rows="4" required></textarea>
                </div>
                
                <button type="submit" class="btn">Add Trainer</button>
            </form>
        </section>
        
        <section class="trainer-list">
            <h2>All Trainers</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Certification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($trainer = $trainers->fetch_assoc()): ?>
                    <tr>
                        <td><?= $trainer['name'] ?></td>
                        <td><?= $trainer['specialization'] ?></td>
                        <td><?= $trainer['certification'] ?></td>
                        <td>
                            <a href="edit_trainer.php?id=<?= $trainer['trainer_id'] ?>" class="btn edit">Edit</a>
                            <a href="trainers.php?delete=<?= $trainer['trainer_id'] ?>" class="btn delete" onclick="return confirm('Delete this trainer?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<!-- php include '../includes/footer.php';  -->