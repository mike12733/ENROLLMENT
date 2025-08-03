<?php
global $pdo;

// Get enrolled students count
function getEnrolledStudentsCount() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE status = 'enrolled'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Get pending students count
function getPendingStudentsCount() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE status = 'pending'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Get male students count
function getMaleStudentsCount() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE gender = 'Male'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Get female students count
function getFemaleStudentsCount() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE gender = 'Female'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Get recent registrations
function getRecentRegistrations() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM students ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $students = $stmt->fetchAll();
    
    $html = '';
    foreach ($students as $student) {
        $status_class = $student['status'] == 'enrolled' ? 'badge bg-success' : 'badge bg-warning';
        $html .= "<tr>
                    <td>{$student['student_id']}</td>
                    <td>{$student['first_name']} {$student['last_name']}</td>
                    <td>{$student['grade_level']}</td>
                    <td><span class='$status_class'>{$student['status']}</span></td>
                    <td>" . date('M d, Y', strtotime($student['created_at'])) . "</td>
                  </tr>";
    }
    return $html;
}

// Get recent announcements
function getRecentAnnouncements() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $announcements = $stmt->fetchAll();
    
    $html = '';
    foreach ($announcements as $announcement) {
        $html .= "<div class='mb-3'>
                    <h6 class='mb-1'>{$announcement['title']}</h6>
                    <p class='text-muted small mb-1'>" . date('M d, Y', strtotime($announcement['created_at'])) . "</p>
                    <p class='mb-0'>{$announcement['content']}</p>
                  </div>";
    }
    return $html;
}

// Get student schedule
function getStudentSchedule($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT s.* FROM schedules s 
                           JOIN student_schedules ss ON s.id = ss.schedule_id 
                           WHERE ss.student_id = ?");
    $stmt->execute([$user_id]);
    $schedules = $stmt->fetchAll();
    
    $html = '';
    foreach ($schedules as $schedule) {
        $html .= "<tr>
                    <td>{$schedule['subject']}</td>
                    <td>{$schedule['time']}</td>
                    <td>{$schedule['day']}</td>
                    <td>{$schedule['room']}</td>
                  </tr>";
    }
    return $html;
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate student ID
function generateStudentId() {
    return 'STU' . date('Y') . rand(1000, 9999);
}

// Check if student exists
function studentExists($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
}

// Create new student
function createStudent($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO students (student_id, first_name, last_name, date_of_birth, 
                           gender, email, phone, grade_level, program, status, created_at) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
    return $stmt->execute([
        $data['student_id'],
        $data['first_name'],
        $data['last_name'],
        $data['date_of_birth'],
        $data['gender'],
        $data['email'],
        $data['phone'],
        $data['grade_level'],
        $data['program']
    ]);
}

// Get all students
function getAllStudents() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM students ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Update student status
function updateStudentStatus($student_id, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE students SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $student_id]);
}

// Delete student
function deleteStudent($student_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    return $stmt->execute([$student_id]);
}

// Create announcement
function createAnnouncement($title, $content, $user_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO announcements (title, content, created_by, created_at) 
                           VALUES (?, ?, ?, NOW())");
    return $stmt->execute([$title, $content, $user_id]);
}

// Get all announcements
function getAllAnnouncements() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT a.*, u.username FROM announcements a 
                           JOIN users u ON a.created_by = u.id 
                           ORDER BY a.created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Authenticate user
function authenticateUser($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

// Create user
function createUser($username, $password, $role) {
    global $pdo;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, created_at) 
                           VALUES (?, ?, ?, NOW())");
    return $stmt->execute([$username, $hashed_password, $role]);
}
?>