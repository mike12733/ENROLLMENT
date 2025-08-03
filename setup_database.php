<?php
require_once 'config/database.php';

try {
    // Drop existing tables if they exist (for clean setup)
    $pdo->exec("DROP TABLE IF EXISTS student_schedules");
    $pdo->exec("DROP TABLE IF EXISTS schedules");
    $pdo->exec("DROP TABLE IF EXISTS announcements");
    $pdo->exec("DROP TABLE IF EXISTS students");
    $pdo->exec("DROP TABLE IF EXISTS users");

    // Create users table
    $pdo->exec("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'registrar', 'teacher', 'student') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Create students table
    $pdo->exec("CREATE TABLE students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(20) UNIQUE NOT NULL,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        date_of_birth DATE NOT NULL,
        gender ENUM('Male', 'Female') NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(20) NOT NULL,
        grade_level VARCHAR(20) NOT NULL,
        program VARCHAR(50) NOT NULL,
        status ENUM('pending', 'enrolled', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Create announcements table
    $pdo->exec("CREATE TABLE announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        content TEXT NOT NULL,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Create schedules table
    $pdo->exec("CREATE TABLE schedules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subject VARCHAR(100) NOT NULL,
        time VARCHAR(50) NOT NULL,
        day VARCHAR(20) NOT NULL,
        room VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Create student_schedules table (many-to-many relationship)
    $pdo->exec("CREATE TABLE student_schedules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        schedule_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Insert default admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->execute(['admin', $admin_password]);

    // Insert default student user
    $student_password = password_hash('student123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'student')");
    $stmt->execute(['student', $student_password]);

    // Insert sample students
    $sample_students = [
        ['STU2024001', 'John', 'Doe', '2006-05-15', 'Male', 'john.doe@email.com', '09123456789', 'Grade 10', 'STEM'],
        ['STU2024002', 'Jane', 'Smith', '2005-08-22', 'Female', 'jane.smith@email.com', '09234567890', 'Grade 11', 'ABM'],
        ['STU2024003', 'Mike', 'Johnson', '2006-03-10', 'Male', 'mike.johnson@email.com', '09345678901', 'Grade 9', 'HUMSS'],
        ['STU2024004', 'Sarah', 'Williams', '2005-12-05', 'Female', 'sarah.williams@email.com', '09456789012', 'Grade 12', 'GAS'],
        ['STU2024005', 'David', 'Brown', '2006-07-18', 'Male', 'david.brown@email.com', '09567890123', 'Grade 10', 'STEM']
    ];

    $stmt = $pdo->prepare("INSERT INTO students (student_id, first_name, last_name, date_of_birth, gender, email, phone, grade_level, program, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($sample_students as $student) {
        $status = rand(0, 1) ? 'enrolled' : 'pending';
        $stmt->execute([...$student, $status]);
    }

    // Insert sample schedules
    $sample_schedules = [
        ['Mathematics', '8:00 AM - 9:00 AM', 'Monday', 'Room 101'],
        ['English', '9:00 AM - 10:00 AM', 'Monday', 'Room 102'],
        ['Science', '10:00 AM - 11:00 AM', 'Monday', 'Room 103'],
        ['History', '11:00 AM - 12:00 PM', 'Monday', 'Room 104'],
        ['Mathematics', '8:00 AM - 9:00 AM', 'Tuesday', 'Room 101'],
        ['English', '9:00 AM - 10:00 AM', 'Tuesday', 'Room 102'],
        ['Science', '10:00 AM - 11:00 AM', 'Tuesday', 'Room 103'],
        ['History', '11:00 AM - 12:00 PM', 'Tuesday', 'Room 104'],
        ['Mathematics', '8:00 AM - 9:00 AM', 'Wednesday', 'Room 101'],
        ['English', '9:00 AM - 10:00 AM', 'Wednesday', 'Room 102'],
        ['Science', '10:00 AM - 11:00 AM', 'Wednesday', 'Room 103'],
        ['History', '11:00 AM - 12:00 PM', 'Wednesday', 'Room 104'],
        ['Mathematics', '8:00 AM - 9:00 AM', 'Thursday', 'Room 101'],
        ['English', '9:00 AM - 10:00 AM', 'Thursday', 'Room 102'],
        ['Science', '10:00 AM - 11:00 AM', 'Thursday', 'Room 103'],
        ['History', '11:00 AM - 12:00 PM', 'Thursday', 'Room 104'],
        ['Mathematics', '8:00 AM - 9:00 AM', 'Friday', 'Room 101'],
        ['English', '9:00 AM - 10:00 AM', 'Friday', 'Room 102'],
        ['Science', '10:00 AM - 11:00 AM', 'Friday', 'Room 103'],
        ['History', '11:00 AM - 12:00 PM', 'Friday', 'Room 104']
    ];

    $stmt = $pdo->prepare("INSERT INTO schedules (subject, time, day, room) VALUES (?, ?, ?, ?)");
    foreach ($sample_schedules as $schedule) {
        $stmt->execute($schedule);
    }

    // Insert sample announcements
    $sample_announcements = [
        ['Welcome to Online Enrollment System', 'Welcome to our new online enrollment system. Students can now register online without the need to visit the school physically. All registration forms are now available online.'],
        ['Enrollment Period Extended', 'The enrollment period has been extended until the end of the month. Please complete your registration as soon as possible. Late registrations will not be accepted.'],
        ['Orientation Schedule', 'Orientation for new students will be held on the first week of classes. Please check your email for the detailed schedule. Attendance is mandatory for all new students.'],
        ['Class Schedule Available', 'Class schedules are now available. Students can view their schedules in their dashboard. Please review your schedule and report any conflicts immediately.'],
        ['Important Reminder', 'Please ensure all required documents are submitted during registration. Incomplete applications will not be processed. Required documents include birth certificate, report card, and 2x2 photo.']
    ];

    $stmt = $pdo->prepare("INSERT INTO announcements (title, content, created_by) VALUES (?, ?, 1)");
    foreach ($sample_announcements as $announcement) {
        $stmt->execute($announcement);
    }

    // Assign some schedules to students
    $stmt = $pdo->prepare("INSERT INTO student_schedules (student_id, schedule_id) VALUES (?, ?)");
    for ($i = 1; $i <= 5; $i++) {
        for ($j = 1; $j <= 4; $j++) {
            $stmt->execute([$i, $j]);
        }
    }

    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f8f9fa; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1);'>";
    echo "<h1 style='color: #28a745; text-align: center;'>✅ Database Setup Completed Successfully!</h1>";
    echo "<div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>📊 Database Tables Created:</h3>";
    echo "<ul>";
    echo "<li><strong>users</strong> - User accounts and roles</li>";
    echo "<li><strong>students</strong> - Student registration data</li>";
    echo "<li><strong>announcements</strong> - System announcements</li>";
    echo "<li><strong>schedules</strong> - Class schedules</li>";
    echo "<li><strong>student_schedules</strong> - Student-schedule relationships</li>";
    echo "</ul>";
    
    echo "<h3>👥 Default Users Created:</h3>";
    echo "<div style='background: #e9ecef; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<p><strong>Admin Account:</strong></p>";
    echo "<p>Username: <code>admin</code></p>";
    echo "<p>Password: <code>admin123</code></p>";
    echo "</div>";
    echo "<div style='background: #e9ecef; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<p><strong>Student Account:</strong></p>";
    echo "<p>Username: <code>student</code></p>";
    echo "<p>Password: <code>student123</code></p>";
    echo "</div>";
    
    echo "<h3>📈 Sample Data Added:</h3>";
    echo "<ul>";
    echo "<li>5 sample students with different statuses</li>";
    echo "<li>20 class schedules</li>";
    echo "<li>5 announcements</li>";
    echo "<li>Sample student-schedule assignments</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin-top: 30px;'>";
    echo "<a href='login.php' style='background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;'>🚀 Access the System</a>";
    echo "</div>";
    echo "</div>";

} catch(PDOException $e) {
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f8d7da; border-radius: 10px; border: 1px solid #f5c6cb;'>";
    echo "<h1 style='color: #721c24; text-align: center;'>❌ Database Setup Failed</h1>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database connection settings in <code>config/database.php</code></p>";
    echo "</div>";
}
?>