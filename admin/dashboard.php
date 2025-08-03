<?php
require_once '../config/database.php';
require_once '../includes/session.php';
require_once '../includes/functions.php';

requireRole('admin');

$db = new Database();
$pdo = $db->getConnection();

// Handle form submissions
$message = '';
$messageType = '';

// Handle student status updates
if (isset($_POST['update_status'])) {
    $studentId = $_POST['student_id'];
    $newStatus = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE students SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $studentId]);
        $message = "Student status updated successfully!";
        $messageType = "success";
    } catch (PDOException $e) {
        $message = "Error updating student status.";
        $messageType = "error";
    }
}

// Handle new announcement
if (isset($_POST['add_announcement'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $targetAudience = $_POST['target_audience'];
    
    if (!empty($title) && !empty($content)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO announcements (title, content, author_id, target_audience) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $_SESSION['user_id'], $targetAudience]);
            $message = "Announcement added successfully!";
            $messageType = "success";
        } catch (PDOException $e) {
            $message = "Error adding announcement.";
            $messageType = "error";
        }
    } else {
        $message = "Please fill in all announcement fields.";
        $messageType = "error";
    }
}

// Handle student deletion
if (isset($_POST['delete_student'])) {
    $studentId = $_POST['student_id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$studentId]);
        $message = "Student deleted successfully!";
        $messageType = "success";
    } catch (PDOException $e) {
        $message = "Error deleting student.";
        $messageType = "error";
    }
}

// Get dashboard data
$counts = getStudentCounts($pdo);

// Get all students
$stmt = $pdo->prepare("SELECT * FROM students ORDER BY created_at DESC");
$stmt->execute();
$students = $stmt->fetchAll();

// Get recent announcements
$announcements = getRecentAnnouncements($pdo, 10);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Enrollment System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">Admin Panel</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="dashboard.php">Admin Dashboard</a></li>
                    <li><a href="../dashboard.php">Main Dashboard</a></li>
                    <li><a href="../logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1 style="color: white; margin-bottom: 2rem;">Admin Dashboard</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <!-- Dashboard Tiles -->
        <div class="dashboard-tiles">
            <div class="tile tile-enrolled">
                <div class="tile-number"><?php echo $counts['enrolled']; ?></div>
                <div class="tile-label">Enrolled Students</div>
            </div>
            <div class="tile tile-pending">
                <div class="tile-number"><?php echo $counts['pending']; ?></div>
                <div class="tile-label">Pending Applicants</div>
            </div>
            <div class="tile tile-male">
                <div class="tile-number"><?php echo $counts['male']; ?></div>
                <div class="tile-label">Male Students</div>
            </div>
            <div class="tile tile-female">
                <div class="tile-number"><?php echo $counts['female']; ?></div>
                <div class="tile-label">Female Students</div>
            </div>
        </div>

        <!-- Add New Announcement -->
        <div class="form-container">
            <h2 class="form-title">📢 Add New Announcement</h2>
            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-section">
                        <div class="form-group">
                            <label for="title">Announcement Title *</label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="content">Content *</label>
                            <textarea id="content" name="content" required rows="4" 
                                      style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="target_audience">Target Audience</label>
                            <select id="target_audience" name="target_audience">
                                <option value="all">All Users</option>
                                <option value="students">Students Only</option>
                                <option value="teachers">Teachers Only</option>
                                <option value="admin">Admin Only</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="add_announcement" class="btn btn-primary">
                            Add Announcement
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Student Management -->
        <div class="table-container">
            <div class="table-title">👥 Student Management</div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Grade Level</th>
                            <th>Program</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: #666;">No students registered yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['grade_level']); ?></td>
                                    <td><?php echo htmlspecialchars($student['program']); ?></td>
                                    <td>
                                        <span style="padding: 4px 12px; border-radius: 12px; font-size: 0.875rem; font-weight: 500; 
                                                     background: <?php 
                                                         echo $student['status'] === 'enrolled' ? '#d4edda' : 
                                                             ($student['status'] === 'pending' ? '#fff3cd' : '#f8d7da'); 
                                                     ?>; 
                                                     color: <?php 
                                                         echo $student['status'] === 'enrolled' ? '#155724' : 
                                                             ($student['status'] === 'pending' ? '#856404' : '#721c24'); 
                                                     ?>;">
                                            <?php echo ucfirst($student['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                            <!-- Status Update Form -->
                                            <form method="POST" action="" style="display: inline;">
                                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                <select name="status" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #ddd;">
                                                    <option value="pending" <?php echo $student['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="enrolled" <?php echo $student['status'] === 'enrolled' ? 'selected' : ''; ?>>Enrolled</option>
                                                    <option value="rejected" <?php echo $student['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                </select>
                                                <button type="submit" name="update_status" class="btn btn-success" 
                                                        style="padding: 4px 8px; font-size: 0.875rem;">Update</button>
                                            </form>
                                            
                                            <!-- Delete Form -->
                                            <form method="POST" action="" style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                <button type="submit" name="delete_student" class="btn btn-danger" 
                                                        style="padding: 4px 8px; font-size: 0.875rem;">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="announcements">
            <h2 style="margin-bottom: 1.5rem; color: #333;">📋 Recent Announcements</h2>
            <?php if (empty($announcements)): ?>
                <div class="announcement-item">
                    <div class="announcement-title">No Announcements</div>
                    <div class="announcement-content">No announcements have been posted yet.</div>
                </div>
            <?php else: ?>
                <?php foreach ($announcements as $announcement): ?>
                    <div class="announcement-item">
                        <div class="announcement-title"><?php echo htmlspecialchars($announcement['title']); ?></div>
                        <div class="announcement-content"><?php echo htmlspecialchars($announcement['content']); ?></div>
                        <div class="announcement-meta">
                            Target: <?php echo ucfirst($announcement['target_audience']); ?> • 
                            By <?php echo htmlspecialchars($announcement['author']); ?> • 
                            <?php echo date('M j, Y g:i A', strtotime($announcement['created_at'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>