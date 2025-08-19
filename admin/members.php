<?php
include '../includes/config.php';
include '../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle member deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM members WHERE member_id = $id");
    header("Location: members.php?deleted=1");
}

$members = $conn->query("SELECT * FROM members ORDER BY join_date DESC");
?>
<!-- php include '../includes/header.php';  -->
 <link rel="stylesheet" href="../assets/css/admin/members.css ">

<div class="admin-container">
    <?php include 'sidebar.php'; ?>
    
    <main class="admin-content">
        <h1>Member Management</h1>
        
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert success">Member deleted successfully</div>
        <?php endif; ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Join Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($member = $members->fetch_assoc()): ?>
                <tr>
                    <td><?= $member['member_id'] ?></td>
                    <td><?= $member['name'] ?></td>
                    <td><?= $member['email'] ?></td>
                    <td><?= date('M j, Y', strtotime($member['join_date'])) ?></td>
                    <td>
                        <a href="edit_member.php?id=<?= $member['member_id'] ?>" class="btn edit">Edit</a>
                        <a href="members.php?delete=<?= $member['member_id'] ?>" class="btn delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>

<!-- php include '../includes/footer.php';  -->