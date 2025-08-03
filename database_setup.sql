-- Create Database
CREATE DATABASE IF NOT EXISTS online_enrollment_system;
USE online_enrollment_system;

-- Users table for authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'registrar', 'teacher', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    grade_level VARCHAR(20) NOT NULL,
    program VARCHAR(100) NOT NULL,
    status ENUM('enrolled', 'pending', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Subjects table
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_code VARCHAR(10) UNIQUE NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    description TEXT,
    units INT DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Class schedules table
CREATE TABLE class_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    teacher_id INT,
    room VARCHAR(20) NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    grade_level VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Student enrollments table
CREATE TABLE student_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    schedule_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES class_schedules(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, schedule_id)
);

-- Announcements table
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    target_audience ENUM('all', 'students', 'teachers', 'admin') DEFAULT 'all',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert default admin user
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample subjects
INSERT INTO subjects (subject_code, subject_name, description, units) VALUES
('MATH101', 'Mathematics 1', 'Basic Mathematics for Grade 7', 3),
('ENG101', 'English 1', 'Basic English Communication', 3),
('SCI101', 'Science 1', 'General Science for Grade 7', 3),
('FIL101', 'Filipino 1', 'Komunikasyon sa Filipino', 3),
('PE101', 'Physical Education 1', 'Basic Physical Education', 2),
('HIST101', 'History 1', 'Philippine History', 3);

-- Insert sample class schedules
INSERT INTO class_schedules (subject_id, room, day_of_week, start_time, end_time, grade_level) VALUES
(1, 'Room 101', 'Monday', '08:00:00', '09:00:00', 'Grade 7'),
(2, 'Room 102', 'Monday', '09:00:00', '10:00:00', 'Grade 7'),
(3, 'Room 103', 'Tuesday', '08:00:00', '09:00:00', 'Grade 7'),
(4, 'Room 104', 'Tuesday', '09:00:00', '10:00:00', 'Grade 7'),
(5, 'Gymnasium', 'Wednesday', '08:00:00', '09:00:00', 'Grade 7'),
(6, 'Room 105', 'Thursday', '08:00:00', '09:00:00', 'Grade 7');

-- Insert sample announcements
INSERT INTO announcements (title, content, author_id, target_audience) VALUES
('Welcome to Online Enrollment', 'Welcome to our new online enrollment system. Please complete your registration.', 1, 'students'),
('Enrollment Deadline', 'Reminder: Enrollment deadline is approaching. Please submit all requirements.', 1, 'all'),
('Class Schedule Updates', 'Please check your class schedules for any recent updates.', 1, 'students');