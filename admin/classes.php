<?php
include '../includes/config.php';
include '../includes/auth.php';
redirectIfNotAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Input sanitization
    $class_name = sanitize($_POST['class_name']);
    $trainer_id = (int)$_POST['trainer_id'];
    $class_type = sanitize($_POST['class_type']);
    $max_capacity = (int)$_POST['max_capacity'];
    
    // Convert datetime inputs
    $start_time = date('Y-m-d H:i:s', strtotime($_POST['start_time']));
    $end_time = date('Y-m-d H:i:s', strtotime($_POST['end_time']));
    
    // Validate datetimes
    if ($start_time == '1970-01-01 00:00:00' || $end_time == '1970-01-01 00:00:00') {
        die("Invalid datetime format received. Please check your form inputs.");
    }
    
    // Check if end time is after start time
    if (strtotime($end_time) <= strtotime($start_time)) {
        die("End time must be after start time");
    }
    
    // Prepare and execute query
    $stmt = $conn->prepare("INSERT INTO classes 
                          (class_name, trainer_id, class_type, start_time, end_time, max_capacity) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssi", 
        $class_name,
        $trainer_id,
        $class_type,
        $start_time,
        $end_time,
        $max_capacity
    );
    
    if ($stmt->execute()) {
        $success = "Class created successfully!";
    } else {
        $error = "Error creating class: " . $stmt->error;
        error_log("MySQL Error: " . $stmt->error);
        
        // Handle specific datetime errors
        if (strpos($stmt->error, 'datetime') !== false) {
            $error .= " - Please check your date/time values";
        }
    }
}

// Handle class deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($conn->query("DELETE FROM classes WHERE class_id = $id")) {
        header("Location: classes.php?deleted=1");
        exit();
    } else {
        $error = "Error deleting class: " . $conn->error;
    }
}

// Get all classes with trainer names
$classes = $conn->query("SELECT c.*, t.name as trainer_name 
                        FROM classes c 
                        JOIN trainers t ON c.trainer_id = t.trainer_id
                        ORDER BY c.start_time DESC");

// Get all trainers for dropdown
$trainers = $conn->query("SELECT * FROM trainers ORDER BY name ASC");
?>

    <link rel="stylesheet" href="../assets/css/admin/classes.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="header">
                <h1><i class="fas fa-dumbbell"></i> Class Management</h1>
                <?php if(isset($_GET['deleted'])): ?>
                    <div class="alert success">
                        <i class="fas fa-check-circle"></i> Class deleted successfully
                    </div>
                <?php endif; ?>
                <?php if(isset($success)): ?>
                    <div class="alert success">
                        <i class="fas fa-check-circle"></i> <?= $success ?>
                    </div>
                <?php endif; ?>
                <?php if(isset($error)): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <h2><i class="fas fa-plus-circle"></i> Add New Class</h2>
                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="class_name"><i class="fas fa-tag"></i> Class Name</label>
                            <input type="text" id="class_name" name="class_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="trainer_id"><i class="fas fa-user-tie"></i> Trainer</label>
                            <select id="trainer_id" name="trainer_id" required>
                                <?php if($trainers->num_rows > 0): ?>
                                    <?php while($trainer = $trainers->fetch_assoc()): ?>
                                        <option value="<?= $trainer['trainer_id'] ?>"><?= $trainer['name'] ?></option>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <option value="">No trainers available</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="class_type"><i class="fas fa-layer-group"></i> Class Type</label>
                            <select id="class_type" name="class_type" required>
                                <option value="yoga">Yoga</option>
                                <option value="cardio">Cardio</option>
                                <option value="strength">Strength Training</option>
                                <option value="hiit">HIIT</option>
                                <option value="dance">Dance</option>
                                <option value="cycling">Cycling</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="max_capacity"><i class="fas fa-users"></i> Max Capacity</label>
                            <input type="number" id="max_capacity" name="max_capacity" min="1" value="20" required>
                        </div>
                        
                        <div class="form-group time-group">
                            <label><i class="fas fa-clock"></i> Start Time</label>
                            <input type="datetime-local" name="start_time" required>
                        </div>
                        
                        <div class="form-group time-group">
                            <label><i class="fas fa-clock"></i> End Time</label>
                            <input type="datetime-local" name="end_time" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn primary">
                        <i class="fas fa-save"></i> Create Class
                    </button>
                </form>
            </div>
            
            <div class="card">
                <div class="table-header">
                    <h2><i class="fas fa-list"></i> All Classes</h2>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search classes...">
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Type</th>
                                <th>Trainer</th>
                                <th>Schedule</th>
                                <th>Capacity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($classes->num_rows > 0): ?>
                                <?php while($class = $classes->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($class['class_name']) ?></td>
                                        <td>
                                            <span class="class-type <?= $class['class_type'] ?>">
                                                <?= ucfirst($class['class_type']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($class['trainer_name']) ?></td>
                                        <td>
                                            <?= date('D, M j, Y', strtotime($class['start_time'])) ?><br>
                                            <?= date('g:i A', strtotime($class['start_time'])) ?> - <?= date('g:i A', strtotime($class['end_time'])) ?>
                                        </td>
                                        <td>
                                            <div class="capacity-bar">
                                                <div class="progress" style="width: <?= ($class['current_bookings']/$class['max_capacity'])*100 ?>%"></div>
                                                <span><?= $class['current_bookings'] ?>/<?= $class['max_capacity'] ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="edit_class.php?id=<?= $class['class_id'] ?>" class="btn edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="classes.php?delete=<?= $class['class_id'] ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this class?')">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="no-data">
                                        <i class="fas fa-info-circle"></i> No classes found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin/classes.js"></script>