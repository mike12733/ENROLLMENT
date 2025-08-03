<?php
require_once 'config/database.php';

try {
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'registrar', 'teacher', 'student') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create students table
    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
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
    )");

    // Create announcements table
    $pdo->exec("CREATE TABLE IF NOT EXISTS announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        content TEXT NOT NULL,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Create schedules table
    $pdo->exec("CREATE TABLE IF NOT EXISTS schedules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subject VARCHAR(100) NOT NULL,
        time VARCHAR(50) NOT NULL,
        day VARCHAR(20) NOT NULL,
        room VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create student_schedules table (many-to-many relationship)
    $pdo->exec("CREATE TABLE IF NOT EXISTS student_schedules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        schedule_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE
    )");

    // Insert default admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->execute(['admin', $admin_password]);

    // Insert default student user
    $student_password = password_hash('student123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, password, role) VALUES (?, ?, 'student')");
    $stmt->execute(['student', $student_password]);

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

    $stmt = $pdo->prepare("INSERT IGNORE INTO schedules (subject, time, day, room) VALUES (?, ?, ?, ?)");
    foreach ($sample_schedules as $schedule) {
        $stmt->execute($schedule);
    }

    // Insert sample announcements
    $sample_announcements = [
        ['Welcome to Online Enrollment System', 'Welcome to our new online enrollment system. Students can now register online without the need to visit the school physically.'],
        ['Enrollment Period Extended', 'The enrollment period has been extended until the end of the month. Please complete your registration as soon as possible.'],
        ['Orientation Schedule', 'Orientation for new students will be held on the first week of classes. Please check your email for the detailed schedule.'],
        ['Class Schedule Available', 'Class schedules are now available. Students can view their schedules in their dashboard.'],
        ['Important Reminder', 'Please ensure all required documents are submitted during registration. Incomplete applications will not be processed.']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO announcements (title, content, created_by) VALUES (?, ?, 1)");
    foreach ($sample_announcements as $announcement) {
        $stmt->execute($announcement);
    }

    echo "Database setup completed successfully!<br>";
    echo "Default users created:<br>";
    echo "- Admin: admin/admin123<br>";
    echo "- Student: student/student123<br>";
    echo "<br>You can now access the system at: <a href='login.php'>Login Page</a>";

} catch(PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}
?>