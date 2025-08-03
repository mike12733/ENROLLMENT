-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 01, 2024 at 12:00 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enrollment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','registrar','teacher','student') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2024-12-01 12:00:00'),
(2, 'student', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '2024-12-01 12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `grade_level` varchar(20) NOT NULL,
  `program` varchar(50) NOT NULL,
  `status` enum('pending','enrolled','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `email`, `phone`, `grade_level`, `program`, `status`, `created_at`) VALUES
(1, 'STU2024001', 'John', 'Doe', '2006-05-15', 'Male', 'john.doe@email.com', '09123456789', 'Grade 10', 'STEM', 'enrolled', '2024-12-01 12:00:00'),
(2, 'STU2024002', 'Jane', 'Smith', '2005-08-22', 'Female', 'jane.smith@email.com', '09234567890', 'Grade 11', 'ABM', 'enrolled', '2024-12-01 12:00:00'),
(3, 'STU2024003', 'Mike', 'Johnson', '2006-03-10', 'Male', 'mike.johnson@email.com', '09345678901', 'Grade 9', 'HUMSS', 'pending', '2024-12-01 12:00:00'),
(4, 'STU2024004', 'Sarah', 'Williams', '2005-12-05', 'Female', 'sarah.williams@email.com', '09456789012', 'Grade 12', 'GAS', 'enrolled', '2024-12-01 12:00:00'),
(5, 'STU2024005', 'David', 'Brown', '2006-07-18', 'Male', 'david.brown@email.com', '09567890123', 'Grade 10', 'STEM', 'pending', '2024-12-01 12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `created_by`, `created_at`) VALUES
(1, 'Welcome to Online Enrollment System', 'Welcome to our new online enrollment system. Students can now register online without the need to visit the school physically. All registration forms are now available online.', 1, '2024-12-01 12:00:00'),
(2, 'Enrollment Period Extended', 'The enrollment period has been extended until the end of the month. Please complete your registration as soon as possible. Late registrations will not be accepted.', 1, '2024-12-01 12:00:00'),
(3, 'Orientation Schedule', 'Orientation for new students will be held on the first week of classes. Please check your email for the detailed schedule. Attendance is mandatory for all new students.', 1, '2024-12-01 12:00:00'),
(4, 'Class Schedule Available', 'Class schedules are now available. Students can view their schedules in their dashboard. Please review your schedule and report any conflicts immediately.', 1, '2024-12-01 12:00:00'),
(5, 'Important Reminder', 'Please ensure all required documents are submitted during registration. Incomplete applications will not be processed. Required documents include birth certificate, report card, and 2x2 photo.', 1, '2024-12-01 12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `day` varchar(20) NOT NULL,
  `room` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `subject`, `time`, `day`, `room`, `created_at`) VALUES
(1, 'Mathematics', '8:00 AM - 9:00 AM', 'Monday', 'Room 101', '2024-12-01 12:00:00'),
(2, 'English', '9:00 AM - 10:00 AM', 'Monday', 'Room 102', '2024-12-01 12:00:00'),
(3, 'Science', '10:00 AM - 11:00 AM', 'Monday', 'Room 103', '2024-12-01 12:00:00'),
(4, 'History', '11:00 AM - 12:00 PM', 'Monday', 'Room 104', '2024-12-01 12:00:00'),
(5, 'Mathematics', '8:00 AM - 9:00 AM', 'Tuesday', 'Room 101', '2024-12-01 12:00:00'),
(6, 'English', '9:00 AM - 10:00 AM', 'Tuesday', 'Room 102', '2024-12-01 12:00:00'),
(7, 'Science', '10:00 AM - 11:00 AM', 'Tuesday', 'Room 103', '2024-12-01 12:00:00'),
(8, 'History', '11:00 AM - 12:00 PM', 'Tuesday', 'Room 104', '2024-12-01 12:00:00'),
(9, 'Mathematics', '8:00 AM - 9:00 AM', 'Wednesday', 'Room 101', '2024-12-01 12:00:00'),
(10, 'English', '9:00 AM - 10:00 AM', 'Wednesday', 'Room 102', '2024-12-01 12:00:00'),
(11, 'Science', '10:00 AM - 11:00 AM', 'Wednesday', 'Room 103', '2024-12-01 12:00:00'),
(12, 'History', '11:00 AM - 12:00 PM', 'Wednesday', 'Room 104', '2024-12-01 12:00:00'),
(13, 'Mathematics', '8:00 AM - 9:00 AM', 'Thursday', 'Room 101', '2024-12-01 12:00:00'),
(14, 'English', '9:00 AM - 10:00 AM', 'Thursday', 'Room 102', '2024-12-01 12:00:00'),
(15, 'Science', '10:00 AM - 11:00 AM', 'Thursday', 'Room 103', '2024-12-01 12:00:00'),
(16, 'History', '11:00 AM - 12:00 PM', 'Thursday', 'Room 104', '2024-12-01 12:00:00'),
(17, 'Mathematics', '8:00 AM - 9:00 AM', 'Friday', 'Room 101', '2024-12-01 12:00:00'),
(18, 'English', '9:00 AM - 10:00 AM', 'Friday', 'Room 102', '2024-12-01 12:00:00'),
(19, 'Science', '10:00 AM - 11:00 AM', 'Friday', 'Room 103', '2024-12-01 12:00:00'),
(20, 'History', '11:00 AM - 12:00 PM', 'Friday', 'Room 104', '2024-12-01 12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `student_schedules`
--

CREATE TABLE `student_schedules` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_schedules`
--

INSERT INTO `student_schedules` (`id`, `student_id`, `schedule_id`, `created_at`) VALUES
(1, 1, 1, '2024-12-01 12:00:00'),
(2, 1, 2, '2024-12-01 12:00:00'),
(3, 1, 3, '2024-12-01 12:00:00'),
(4, 1, 4, '2024-12-01 12:00:00'),
(5, 2, 1, '2024-12-01 12:00:00'),
(6, 2, 2, '2024-12-01 12:00:00'),
(7, 2, 3, '2024-12-01 12:00:00'),
(8, 2, 4, '2024-12-01 12:00:00'),
(9, 3, 1, '2024-12-01 12:00:00'),
(10, 3, 2, '2024-12-01 12:00:00'),
(11, 3, 3, '2024-12-01 12:00:00'),
(12, 3, 4, '2024-12-01 12:00:00'),
(13, 4, 1, '2024-12-01 12:00:00'),
(14, 4, 2, '2024-12-01 12:00:00'),
(15, 4, 3, '2024-12-01 12:00:00'),
(16, 4, 4, '2024-12-01 12:00:00'),
(17, 5, 1, '2024-12-01 12:00:00'),
(18, 5, 2, '2024-12-01 12:00:00'),
(19, 5, 3, '2024-12-01 12:00:00'),
(20, 5, 4, '2024-12-01 12:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_schedules`
--
ALTER TABLE `student_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `student_schedules`
--
ALTER TABLE `student_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_schedules`
--
ALTER TABLE `student_schedules`
  ADD CONSTRAINT `student_schedules_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_schedules_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;