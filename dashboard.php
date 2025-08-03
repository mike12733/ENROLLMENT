<?php
require_once 'config/database.php';
require_once 'includes/session.php';
require_once 'includes/functions.php';

requireLogin();

$db = new Database();
$pdo = $db->getConnection();

// Get student counts for dashboard tiles
$counts = getStudentCounts($pdo);

// Get student's schedule if they are a student
$schedule = [];
if ($_SESSION['role'] === 'student') {
    // Get student record
    $stmt = $pdo->prepare("SELECT id FROM students WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $student = $stmt->fetch();
    
    if ($student) {
        $schedule = getStudentSchedule($pdo, $student['id']);
    }
} else {
    // For non-students, show sample schedule
    $stmt = $pdo->prepare("SELECT s.subject_name, s.subject_code, cs.day_of_week, cs.start_time, cs.end_time, cs.room
                           FROM class_schedules cs
                           JOIN subjects s ON cs.subject_id = s.id
                           ORDER BY cs.day_of_week, cs.start_time
                           LIMIT 6");
    $stmt->execute();
    $schedule = $stmt->fetchAll();
}

// Get recent announcements
$announcements = getRecentAnnouncements($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Online Enrollment System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">Online Enrollment System</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="register.php">Register Student</a></li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin/dashboard.php">Admin Panel</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1 style="color: white; margin-bottom: 2rem;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        
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

        <!-- Class Schedule -->
        <div class="table-container">
            <div class="table-title">
                <?php echo ($_SESSION['role'] === 'student') ? 'Your Class Schedule' : 'Sample Class Schedule'; ?>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Subject Code</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($schedule)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #666;">
                                <?php echo ($_SESSION['role'] === 'student') ? 'No classes enrolled yet.' : 'No schedule available.'; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($schedule as $class): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($class['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($class['subject_code']); ?></td>
                                <td><?php echo htmlspecialchars($class['day_of_week']); ?></td>
                                <td>
                                    <?php 
                                    echo date('g:i A', strtotime($class['start_time'])) . ' - ' . 
                                         date('g:i A', strtotime($class['end_time'])); 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($class['room']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Recent Announcements -->
        <div class="announcements">
            <h2 style="margin-bottom: 1.5rem; color: #333;">Recent Announcements</h2>
            <?php if (empty($announcements)): ?>
                <div class="announcement-item">
                    <div class="announcement-title">No Announcements</div>
                    <div class="announcement-content">There are no recent announcements at this time.</div>
                </div>
            <?php else: ?>
                <?php foreach ($announcements as $announcement): ?>
                    <div class="announcement-item">
                        <div class="announcement-title"><?php echo htmlspecialchars($announcement['title']); ?></div>
                        <div class="announcement-content"><?php echo htmlspecialchars($announcement['content']); ?></div>
                        <div class="announcement-meta">
                            By <?php echo htmlspecialchars($announcement['author']); ?> • 
                            <?php echo date('M j, Y g:i A', strtotime($announcement['created_at'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>