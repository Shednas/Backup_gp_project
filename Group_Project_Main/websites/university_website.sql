-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Aug 06, 2025 at 06:42 AM
-- Server version: 11.8.2-MariaDB-ubu2404
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `university_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `due_date` datetime NOT NULL,
  `max_score` int(11) DEFAULT 100,
  `weight_percentage` decimal(5,2) DEFAULT 10.00,
  `file_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_published` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `course_id`, `title`, `description`, `instructions`, `due_date`, `max_score`, `weight_percentage`, `file_path`, `created_at`, `updated_at`, `is_published`) VALUES
(2, 9, 'asd', 'asd', NULL, '2025-08-14 13:09:00', 100, 10.00, NULL, '2025-08-05 17:24:18', '2025-08-05 17:24:18', 1),
(3, 9, 'asd', 'asd', 'asd', '2025-08-29 23:17:00', 100, 10.00, '/assets/files/assignments/1754414874_24813026_OS_Sandesh_Thapa_Report.docx', '2025-08-05 17:27:54', '2025-08-05 17:27:54', 1),
(4, 9, 'adsas', 'dasd', 'asda', '2025-08-28 23:45:00', 100, 10.00, '', '2025-08-05 18:00:16', '2025-08-05 18:00:16', 1),
(5, 9, 'adsas', 'dasd', 'asda', '2025-08-28 23:45:00', 100, 10.00, '', '2025-08-05 18:00:50', '2025-08-05 18:00:50', 1),
(6, 9, 'adsas', 'dasd', 'asda', '2025-08-28 23:45:00', 100, 10.00, '', '2025-08-05 18:01:02', '2025-08-05 18:01:02', 1),
(7, 9, 'adsas', 'dasd', 'asda', '2025-08-28 23:45:00', 100, 10.00, '', '2025-08-05 18:01:03', '2025-08-05 18:01:03', 1),
(8, 9, 'adsas', 'dasd', 'asda', '2025-08-28 23:45:00', 100, 10.00, '', '2025-08-05 18:01:03', '2025-08-05 18:01:03', 1),
(9, 9, 'adsas', 'dasd', 'asda', '2025-08-28 23:45:00', 100, 10.00, '', '2025-08-05 18:01:14', '2025-08-05 18:01:14', 1),
(10, 9, 'adsas', 'dasd', 'asda', '2025-08-28 23:45:00', 100, 10.00, '', '2025-08-05 18:01:14', '2025-08-05 18:01:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_submissions`
--

CREATE TABLE `assignment_submissions` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `submission_text` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT current_timestamp(),
  `late_submission` tinyint(1) DEFAULT 0,
  `grade` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `graded_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `assignment_submissions`
--

INSERT INTO `assignment_submissions` (`id`, `assignment_id`, `student_id`, `file_path`, `submission_text`, `submitted_at`, `late_submission`, `grade`, `feedback`, `graded_at`, `graded_by`) VALUES
(2, 2, 15, '/assets/files/submissions/1754416759_24813026_OS_Sandesh_Thapa_Report.docx', '', '2025-08-05 17:59:19', 0, 22.00, '22', '2025-08-05 18:01:27', 4);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `lecturer` varchar(255) DEFAULT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `credits` int(11) NOT NULL DEFAULT 3,
  `image_course` varchar(255) DEFAULT NULL,
  `term` varchar(10) DEFAULT NULL,
  `contents` varchar(255) DEFAULT NULL,
  `assignment` varchar(255) DEFAULT NULL,
  `assignment_submission` varchar(255) DEFAULT NULL,
  `grades` text DEFAULT NULL,
  `course_notice` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `title`, `description`, `lecturer`, `lecturer_id`, `credits`, `image_course`, `term`, `contents`, `assignment`, `assignment_submission`, `grades`, `course_notice`) VALUES
(9, 'CS212', 'Computer', 'test', 'John', 4, 3, '', '2026', 'test.txt', 'test.txt', 'test.txt', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_announcements`
--

CREATE TABLE `course_announcements` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_important` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `course_announcements`
--

INSERT INTO `course_announcements` (`id`, `course_id`, `lecturer_id`, `title`, `content`, `is_important`, `created_at`, `updated_at`) VALUES
(2, 9, 4, 'asdwa', 'dasd', 1, '2025-08-05 17:24:07', '2025-08-05 17:24:07'),
(3, 9, 4, 'asd', 'asd', 1, '2025-08-05 18:00:07', '2025-08-05 18:00:07');

-- --------------------------------------------------------

--
-- Table structure for table `course_grades`
--

CREATE TABLE `course_grades` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `grade_type` enum('assignment','exam','participation','final') NOT NULL,
  `points_earned` decimal(6,2) NOT NULL,
  `points_possible` decimal(6,2) NOT NULL,
  `grade_date` timestamp NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `course_grades`
--

INSERT INTO `course_grades` (`id`, `course_id`, `student_id`, `assignment_id`, `grade_type`, `points_earned`, `points_possible`, `grade_date`, `notes`) VALUES
(2, 9, 15, 2, 'assignment', 22.00, 100.00, '2025-08-05 18:01:27', '22');

-- --------------------------------------------------------

--
-- Table structure for table `course_materials`
--

CREATE TABLE `course_materials` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` timestamp NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `course_materials`
--

INSERT INTO `course_materials` (`id`, `course_id`, `title`, `description`, `file_path`, `file_type`, `file_size`, `uploaded_by`, `upload_date`, `is_active`) VALUES
(1, 9, 'aa', 'test', '/assets/files/courses/1754414452_OS_Report_Template_Sandesh_Thapa.docx', NULL, NULL, 4, '2025-08-05 17:20:52', 1),
(2, 9, 'asd', 'das', '/assets/files/courses/1754416801_24813026_OS_Sandesh_Thapa_Report.docx', NULL, NULL, 4, '2025-08-05 18:00:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `category` enum('academic','sports','cultural','workshop','seminar','other') DEFAULT 'other',
  `max_participants` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','cancelled','completed') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `event_time`, `location`, `category`, `max_participants`, `created_by`, `created_at`, `updated_at`, `status`) VALUES
(4, 'Party', 'Party', '2025-08-07', '12:53:00', 'Northampton', 'cultural', 2, 1, '2025-08-05 16:08:15', '2025-08-05 16:08:15', 'active'),
(5, 'dasd', 'dasd', '2025-08-07', '14:10:00', 'asd', 'other', NULL, 1, '2025-08-06 06:25:57', '2025-08-06 06:25:57', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_courses`
--

CREATE TABLE `lecturer_courses` (
  `id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_at` timestamp NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `lecturer_courses`
--

INSERT INTO `lecturer_courses` (`id`, `lecturer_id`, `course_id`, `assigned_by`, `assigned_at`, `is_active`) VALUES
(5, 4, 4, 2, '2025-07-23 05:16:35', 1),
(8, 4, 6, 1, '2025-08-05 15:42:32', 1),
(9, 4, 5, 1, '2025-08-05 15:42:47', 1),
(10, 4, 7, 1, '2025-08-05 16:04:15', 1),
(11, 4, 9, 1, '2025-08-05 16:34:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 1,
  `target_audience` enum('all','students','lecturers','admins') DEFAULT 'all',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `publish_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `summary`, `image_path`, `is_featured`, `is_published`, `target_audience`, `created_by`, `created_at`, `updated_at`, `publish_date`) VALUES
(1, 'scholarship', 'coming soon', 'appply', '../assets/images/news/1753202357_course_13595_image.jpg', 0, 1, 'all', 2, '2025-07-22 16:39:17', '2025-07-22 16:39:17', '2025-07-22 16:38:00'),
(4, 'test', 'sa', '', '/websites/Main/admin/controllers/../../public/assets/images/news/1754411754_d33e835a00ec8b167e14fdecf54698d3.jpg', 0, 1, 'all', 1, '2025-08-05 16:35:54', '2025-08-05 16:35:54', '2025-08-05 16:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_important` tinyint(1) DEFAULT 0,
  `target_audience` enum('all','students','lecturers','admins') DEFAULT 'all',
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `content`, `is_important`, `target_audience`, `is_active`, `created_by`, `created_at`, `updated_at`, `expires_at`) VALUES
(1, 'exam coming', 'be prepared', 0, 'all', 1, 2, '2025-07-22 16:38:26', '2025-07-22 16:38:26', '2025-07-23 22:23:00'),
(2, 'EXAM', 'EXAM', 1, 'all', 1, 1, '2025-08-05 16:08:40', '2025-08-05 16:08:40', '2025-08-06 21:53:00'),
(3, 'asd', 'asd', 1, 'students', 1, 1, '2025-08-06 06:25:42', '2025-08-06 06:25:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `check_admin` tinyint(1) DEFAULT 0,
  `check_student` tinyint(1) DEFAULT 0,
  `check_lecturer` tinyint(1) DEFAULT 0,
  `super_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `check_admin`, `check_student`, `check_lecturer`, `super_admin`) VALUES
(1, 'admin', 1, 0, 0, 0),
(2, 'student', 0, 1, 0, 0),
(3, 'lecturer', 0, 0, 1, 0),
(4, 'super_admin', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_enrollments`
--

CREATE TABLE `student_enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrollment_date` timestamp NULL DEFAULT current_timestamp(),
  `enrolled_by` int(11) NOT NULL,
  `status` enum('active','dropped','completed') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `student_enrollments`
--

INSERT INTO `student_enrollments` (`id`, `student_id`, `course_id`, `enrollment_date`, `enrolled_by`, `status`) VALUES
(2, 7, 4, '2025-07-23 05:10:52', 2, 'active'),
(3, 8, 5, '2025-07-23 05:11:22', 2, 'active'),
(4, 10, 4, '2025-07-23 05:17:13', 2, 'active'),
(5, 10, 5, '2025-07-23 05:17:13', 2, 'active'),
(6, 13, 6, '2025-08-05 15:42:15', 1, 'active'),
(7, 13, 4, '2025-08-05 15:42:15', 1, 'active'),
(8, 13, 5, '2025-08-05 15:42:15', 1, 'active'),
(9, 15, 9, '2025-08-05 16:34:08', 1, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `created_at`) VALUES
(1, 'admin', '$2y$12$Nmh2bLC1n9vN01og26C/Juj7nG1fWUlEtiDr0emIHJEuK8GG/2aK.', 'admin@gmail.com', 'admin', '2025-07-19 17:08:22'),
(4, 'lecturer', '$2y$12$96YIl1SqaWDm4HLvk/NWlu7Bq2mDVcTH13bmmedp1uEeQQoa8C9Va', 'lecturer@gmail.com', 'lecturer', '2025-07-21 12:54:59'),
(14, 'staff', '$2y$12$8cvX3EBhh/4vMWgwH8OGJen6fZxCk7NVT337L3LNmeZyzkMPaFDYa', 'staff@gmail.com', 'staff thapa', '2025-08-05 15:48:23'),
(15, 'student', '$2y$12$BvK.9EUB4xLiZMvqADIBRudhd0G/RU5PP8dcgtTor6RMPMFBL6rO.', 'student@gmail.com', 'student thapa', '2025-08-05 16:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1),
(14, 1),
(15, 2),
(4, 3),
(1, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assignments_course_id` (`course_id`);

--
-- Indexes for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_submission` (`assignment_id`,`student_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `graded_by` (`graded_by`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_code` (`course_code`),
  ADD KEY `fk_courses_lecturer_id` (`lecturer_id`);

--
-- Indexes for table `course_announcements`
--
ALTER TABLE `course_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `course_grades`
--
ALTER TABLE `course_grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `course_materials`
--
ALTER TABLE `course_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `lecturer_courses`
--
ALTER TABLE `lecturer_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_lecturer_course` (`lecturer_id`,`course_id`),
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `is_published` (`is_published`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `target_audience` (`target_audience`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `is_active` (`is_active`),
  ADD KEY `target_audience` (`target_audience`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_course` (`student_id`,`course_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `enrolled_by` (`enrolled_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `course_announcements`
--
ALTER TABLE `course_announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `course_grades`
--
ALTER TABLE `course_grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course_materials`
--
ALTER TABLE `course_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lecturer_courses`
--
ALTER TABLE `lecturer_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `fk_assignments_course_id` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD CONSTRAINT `assignment_submissions_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignment_submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignment_submissions_ibfk_3` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_courses_lecturer_id` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_announcements`
--
ALTER TABLE `course_announcements`
  ADD CONSTRAINT `course_announcements_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_announcements_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_grades`
--
ALTER TABLE `course_grades`
  ADD CONSTRAINT `course_grades_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_grades_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_grades_ibfk_3` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_materials`
--
ALTER TABLE `course_materials`
  ADD CONSTRAINT `course_materials_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_materials_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
