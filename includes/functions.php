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

// Get student by ID
function getStudentById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
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

// Get announcement by ID
function getAnnouncementById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Update announcement
function updateAnnouncement($id, $title, $content) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE announcements SET title = ?, content = ? WHERE id = ?");
    return $stmt->execute([$title, $content, $id]);
}

// Delete announcement
function deleteAnnouncement($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    return $stmt->execute([$id]);
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

// Update user password
function updateUserPassword($user_id, $new_password) {
    global $pdo;
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    return $stmt->execute([$hashed_password, $user_id]);
}

// Get user by ID
function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get program statistics
function getProgramStats() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT program, COUNT(*) as count FROM students GROUP BY program");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get grade level statistics
function getGradeLevelStats() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT grade_level, COUNT(*) as count FROM students GROUP BY grade_level ORDER BY grade_level");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Search students
function searchStudents($search_term) {
    global $pdo;
    $search_term = "%$search_term%";
    $stmt = $pdo->prepare("SELECT * FROM students WHERE 
                           first_name LIKE ? OR 
                           last_name LIKE ? OR 
                           student_id LIKE ? OR 
                           email LIKE ? 
                           ORDER BY created_at DESC");
    $stmt->execute([$search_term, $search_term, $search_term, $search_term]);
    return $stmt->fetchAll();
}

// Get students by status
function getStudentsByStatus($status) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM students WHERE status = ? ORDER BY created_at DESC");
    $stmt->execute([$status]);
    return $stmt->fetchAll();
}

// Get students by program
function getStudentsByProgram($program) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM students WHERE program = ? ORDER BY created_at DESC");
    $stmt->execute([$program]);
    return $stmt->fetchAll();
}

// Get students by grade level
function getStudentsByGradeLevel($grade_level) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM students WHERE grade_level = ? ORDER BY created_at DESC");
    $stmt->execute([$grade_level]);
    return $stmt->fetchAll();
}

// Export students to CSV
function exportStudentsToCSV($students) {
    $filename = 'students_export_' . date('Y-m-d_H-i-s') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Add CSV headers
    fputcsv($output, ['Student ID', 'First Name', 'Last Name', 'Date of Birth', 'Gender', 'Email', 'Phone', 'Grade Level', 'Program', 'Status', 'Date Registered']);
    
    // Add data rows
    foreach ($students as $student) {
        fputcsv($output, [
            $student['student_id'],
            $student['first_name'],
            $student['last_name'],
            $student['date_of_birth'],
            $student['gender'],
            $student['email'],
            $student['phone'],
            $student['grade_level'],
            $student['program'],
            $student['status'],
            $student['created_at']
        ]);
    }
    
    fclose($output);
    exit();
}

// Send email notification (placeholder function)
function sendEmailNotification($to, $subject, $message) {
    // This would integrate with a real email service
    // For now, we'll just log the email
    error_log("Email would be sent to: $to, Subject: $subject, Message: $message");
    return true;
}

// Generate PDF report (placeholder function)
function generatePDFReport($data) {
    // This would integrate with a PDF library like TCPDF or FPDF
    // For now, we'll just return a success message
    return "PDF report generated successfully";
}

// Validate phone number
function validatePhone($phone) {
    // Remove all non-digit characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Check if it's a valid Philippine mobile number (11 digits starting with 09)
    if (strlen($phone) == 11 && substr($phone, 0, 2) == '09') {
        return true;
    }
    
    return false;
}

// Sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if user has permission
function hasPermission($user_role, $required_role) {
    $role_hierarchy = [
        'admin' => 4,
        'registrar' => 3,
        'teacher' => 2,
        'student' => 1
    ];
    
    return $role_hierarchy[$user_role] >= $role_hierarchy[$required_role];
}

// Get dashboard statistics
function getDashboardStats() {
    return [
        'enrolled' => getEnrolledStudentsCount(),
        'pending' => getPendingStudentsCount(),
        'male' => getMaleStudentsCount(),
        'female' => getFemaleStudentsCount(),
        'total' => getEnrolledStudentsCount() + getPendingStudentsCount()
    ];
}

// Log activity
function logActivity($user_id, $action, $details = '') {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
    return $stmt->execute([$user_id, $action, $details]);
}
?>