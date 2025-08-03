<?php
require_once 'config/database.php';

function getStudentCounts($pdo) {
    $counts = [
        'enrolled' => 0,
        'pending' => 0,
        'male' => 0,
        'female' => 0
    ];
    
    try {
        // Get enrolled students count
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM students WHERE status = 'enrolled'");
        $stmt->execute();
        $counts['enrolled'] = $stmt->fetch()['count'];
        
        // Get pending students count
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM students WHERE status = 'pending'");
        $stmt->execute();
        $counts['pending'] = $stmt->fetch()['count'];
        
        // Get male students count
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM students WHERE gender = 'male'");
        $stmt->execute();
        $counts['male'] = $stmt->fetch()['count'];
        
        // Get female students count
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM students WHERE gender = 'female'");
        $stmt->execute();
        $counts['female'] = $stmt->fetch()['count'];
        
    } catch (PDOException $e) {
        error_log("Error getting student counts: " . $e->getMessage());
    }
    
    return $counts;
}

function getStudentSchedule($pdo, $student_id) {
    try {
        $sql = "SELECT s.subject_name, s.subject_code, cs.day_of_week, cs.start_time, cs.end_time, cs.room
                FROM student_enrollments se
                JOIN class_schedules cs ON se.schedule_id = cs.id
                JOIN subjects s ON cs.subject_id = s.id
                WHERE se.student_id = ?
                ORDER BY cs.day_of_week, cs.start_time";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error getting student schedule: " . $e->getMessage());
        return [];
    }
}

function getRecentAnnouncements($pdo, $limit = 5) {
    try {
        $sql = "SELECT a.title, a.content, a.created_at, u.username as author
                FROM announcements a
                JOIN users u ON a.author_id = u.id
                WHERE a.is_active = 1
                ORDER BY a.created_at DESC
                LIMIT ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error getting announcements: " . $e->getMessage());
        return [];
    }
}

function validateRegistrationData($data) {
    $errors = [];
    
    if (empty($data['first_name'])) {
        $errors[] = "First name is required.";
    }
    
    if (empty($data['last_name'])) {
        $errors[] = "Last name is required.";
    }
    
    if (empty($data['date_of_birth'])) {
        $errors[] = "Date of birth is required.";
    }
    
    if (empty($data['gender']) || !in_array($data['gender'], ['male', 'female'])) {
        $errors[] = "Valid gender is required.";
    }
    
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    
    if (empty($data['phone'])) {
        $errors[] = "Phone number is required.";
    }
    
    if (empty($data['student_id'])) {
        $errors[] = "Student ID is required.";
    }
    
    if (empty($data['grade_level'])) {
        $errors[] = "Grade level is required.";
    }
    
    if (empty($data['program'])) {
        $errors[] = "Program is required.";
    }
    
    return $errors;
}

function registerStudent($pdo, $data) {
    try {
        // Check if student ID or email already exists
        $stmt = $pdo->prepare("SELECT id FROM students WHERE student_id = ? OR email = ?");
        $stmt->execute([$data['student_id'], $data['email']]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Student ID or email already exists.'];
        }
        
        // Insert new student
        $sql = "INSERT INTO students (student_id, first_name, last_name, date_of_birth, gender, email, phone, grade_level, program) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
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
        
        if ($result) {
            return ['success' => true, 'message' => 'Student registered successfully!'];
        } else {
            return ['success' => false, 'message' => 'Registration failed.'];
        }
        
    } catch (PDOException $e) {
        error_log("Error registering student: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred.'];
    }
}
?>