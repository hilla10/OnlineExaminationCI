-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2021 at 08:38 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onlinexaminationci`
--

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lecturer_id` int(11) NOT NULL,
  `teacher_id` char(12) NOT NULL,
  `lecturer_name` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lecturer_id`, `teacher_id`, `lecturer_name`, `email`, `course_id`) VALUES
(1, '12345678', 'Jeremy Watts', 'jeremy@mail.com', 1),
(3, '01234567', 'Conrad Wills', 'conrad@mail.com', 5),
(4, '24000011', 'Danny Test', 'danny@mail.com', 6),
(5, '88888888', 'Thomas', 'thomas@mail.com', 1),
(6, '77777777', 'Demo Lecturer', 'demolecturer@mail.com', 7);

--
-- Triggers `lecturer`
--
DELIMITER $$
CREATE TRIGGER `edit_user_lecturer` BEFORE UPDATE ON `lecturer` FOR EACH ROW UPDATE `users` SET `email` = NEW.email, `username` = NEW.teacher_id WHERE `users`.`username` = OLD.teacher_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_user_lecturer` BEFORE DELETE ON `lecturer` FOR EACH ROW DELETE FROM `users` WHERE `users`.`username` = OLD.teacher_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'Lecturer', 'For making and checking Questions. And also conducting examinations'),
(3, 'Student', 'Exam Participants');

-- --------------------------------------------------------

--
-- Table structure for table `exam_history`
--

CREATE TABLE `exam_history` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `question_list` longtext NOT NULL,
  `answer_list` longtext NOT NULL,
  `correct_count` int(11) NOT NULL,
  `score` decimal(10,2) NOT NULL,
  `weighted_score` decimal(10,2) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `exam_history`
--

INSERT INTO `exam_history` (`id`, `exam_id`, `student_id`, `question_list`, `answer_list`, `correct_count`, `score`, `weighted_score`, `start_time`, `end_time`, `status`) VALUES
(1, 1, 1, '1,2,3', '1:B:N,2:A:N,3:D:N', 3, '100.00', '100.00', '2019-02-16 08:35:05', '2019-02-16 08:36:05', 'N'),
(2, 2, 1, '3,2,1', '3:D:N,2:C:N,1:D:N', 1, '33.00', '100.00', '2019-02-16 10:11:14', '2019-02-16 10:12:14', 'N'),
(3, 3, 1, '5,6', '5:C:N,6:D:N', 2, '100.00', '100.00', '2019-02-16 11:06:25', '2019-02-16 11:07:25', 'N'),
(4, 5, 2, '9,8,10,7,11', '9:D:N,8:B:N,10:C:N,7:B:N,11:D:N', 4, '80.00', '500.00', '2021-12-15 15:21:23', '2021-12-16 00:36:23', 'N'),
(5, 7, 3, '20,17,18,19', '20:E:N,17:C:N,18:C:N,19:D:N', 3, '75.00', '500.00', '2021-12-21 11:41:07', '2021-12-21 11:56:07', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`) VALUES
(1, 'Information System'),
(2, 'Technical Information'),
(3, 'Test Dept'),
(4, 'Demo Department');

-- --------------------------------------------------------

--
-- Table structure for table `department_course`
--

CREATE TABLE `department_course` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department_course`
--

INSERT INTO `department_course` (`id`, `course_id`, `department_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(6, 5, 2),
(7, 6, 3),
(8, 7, 4);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(30) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_name`, `department_id`) VALUES
(1, 'OES229', 1),
(2, 'OES116', 1),
(3, 'OES111', 1),
(7, 'OES119', 2),
(8, 'OES122', 2),
(9, '10', 3),
(10, 'OES250', 1),
(11, 'DEMO10', 4);

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_class`
--

CREATE TABLE `lecturer_class` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lecturer_class`
--

INSERT INTO `lecturer_class` (`id`, `class_id`, `lecturer_id`) VALUES
(1, 3, 1),
(2, 2, 1),
(3, 1, 1),
(9, 2, 3),
(10, 1, 3),
(11, 9, 4),
(12, 10, 5),
(13, 11, 6);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(4, '::1', 'teststudent@gmail.com', 1640078931),
(5, '::1', 'teststudent@gmail.com', 1640078932),
(7, '::1', 'testlecturer@mail.com', 1640158148);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `student_number` char(20) NOT NULL,
  `email` varchar(254) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `class_id` int(11) NOT NULL COMMENT 'class&department'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `name`, `student_number`, `email`, `gender`, `class_id`) VALUES
(1, 'Liam Moore', '12183018', 'liamoore@mail.com', '', 1),
(2, 'Demo Student', '01112004', 'demostd@mail.com', '', 9),
(3, 'Test Student', '1111111111', 'teststudent@mail.com', '', 11);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_name`) VALUES
(1, 'Database Management'),
(2, 'Internet Programming'),
(3, 'Networking'),
(5, 'Control Systems'),
(6, 'Software Engineering'),
(7, 'Demo Course');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `exam_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `exam_name` varchar(200) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `type` enum('Random','Sort') NOT NULL,
  `start_time` datetime NOT NULL,
  `late_time` datetime NOT NULL,
  `token` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`exam_id`, `lecturer_id`, `course_id`, `exam_name`, `total_questions`, `duration`, `type`, `start_time`, `late_time`, `token`) VALUES
(1, 1, 1, 'First Test', 3, 1, 'Random', '2019-02-15 17:25:40', '2019-02-20 17:25:44', 'DPEHL'),
(2, 1, 1, 'Second Test', 3, 1, 'Random', '2019-02-16 10:05:08', '2019-02-17 10:05:10', 'GOEMB'),
(3, 3, 5, 'Try Out 01', 2, 1, 'Random', '2019-02-16 07:00:00', '2019-02-28 14:00:00', 'GETQB'),
(4, 4, 6, 'Tst960', 3, 3, 'Sort', '2021-12-15 11:22:15', '2021-12-15 11:22:18', 'LZXHW'),
(5, 4, 6, 'Weekly Test', 5, 555, 'Random', '2021-12-14 13:25:51', '2021-12-15 20:07:53', 'ESEHT'),
(6, 5, 1, 'First Demo Exam', 4, 9, 'Sort', '2021-12-20 16:59:10', '2021-12-20 17:08:10', 'BTIMQ'),
(7, 6, 7, 'First Terminal Examination', 4, 15, 'Random', '2021-12-21 10:21:04', '2021-12-22 10:30:15', 'TZMEQ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_question`
--

CREATE TABLE `tb_question` (
  `question_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `question` longtext NOT NULL,
  `option_a` longtext NOT NULL,
  `option_b` longtext NOT NULL,
  `option_c` longtext NOT NULL,
  `option_d` longtext NOT NULL,
  `option_e` longtext NOT NULL,
  `file_a` varchar(255) NOT NULL,
  `file_b` varchar(255) NOT NULL,
  `file_c` varchar(255) NOT NULL,
  `file_d` varchar(255) NOT NULL,
  `file_e` varchar(255) NOT NULL,
  `answer` varchar(5) NOT NULL,
  `created_on` int(11) NOT NULL,
  `updated_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_question`
--

INSERT INTO `tb_question` (`question_id`, `lecturer_id`, `course_id`, `weight`, `file`, `file_type`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `file_a`, `file_b`, `file_c`, `file_d`, `file_e`, `answer`, `created_on`, `updated_on`) VALUES
(1, 1, 1, 1, '', '', '<p>Dian : The cake is scrumptious! I love i<br>Joni : … another piece?<br>Dian : Thank you. You should tell me the recipe.<br>Joni : I will.</p><p>Which of the following offering expressions best fill the blank?</p>', '<p>Do you mind if you have</p>', '<p>Would you like</p>', '<p>Shall you hav</p>', '<p>Can I have you</p>', '<p>I will bring you</p>', '', '', '', '', '', 'B', 1550225760, 1550225760),
(2, 1, 1, 1, '', '', '<p>Fitri : The French homework is really hard. I don’t feel like to do it.<br>Rahmat : … to help you?<br>Fitri : It sounds great. Thanks, Rahmat!</p><p><br></p><p>Which of the following offering expressions best fill the blank?</p>', '<p>Would you like me</p>', '<p>Do you mind if I</p>', '<p>Shall I</p>', '<p>Can I</p>', '<p>I will</p>', '', '', '', '', '', 'A', 1550225952, 1550225952),
(3, 1, 1, 1, 'd166959dabe9a81e4567dc44021ea503.jpg', 'image/jpeg', '<p>What is the picture describing?</p><p><small class=\"text-muted\">Sumber gambar: meros.jp</small></p>', '<p>The students are arguing with their lecturer.</p>', '<p>The students are watching their preacher.</p>', '<p>The teacher is angry with their students.</p>', '<p>The students are listening to their lecturer.</p>', '<p>The students detest the preacher.</p>', '', '', '', '', '', 'D', 1550226174, 1550226174),
(5, 3, 5, 1, '', '', '<p>(2000 x 3) : 4 x 0 = ...</p>', '<p>NULL</p>', '<p>NaN</p>', '<p>0</p>', '<p>1</p>', '<p>-1</p>', '', '', '', '', '', 'C', 1550289702, 1550289724),
(6, 3, 5, 1, '98a79c067fefca323c56ed0f8d1cac5f.png', 'image/png', '<p>Nomor berapakah ini?</p>', '<p>Sembilan</p>', '<p>Sepuluh</p>', '<p>Satu</p>', '<p>Tujuh</p>', '<p>Tiga</p>', '', '', '', '', '', 'D', 1550289774, 1550289774),
(7, 4, 6, 5, '', '', '<p>Is this a demo test?</p>', '<p>Ye</p>', '<p>Yes</p>', '<p>No</p>', '<p>None</p>', '<p>All</p>', '', '', '', '', '', 'B', 1639498101, 1639498101),
(8, 4, 6, 5, '', '', '<p>Is this a sample question?</p>', '<p>No</p>', '<p>Yes</p>', '<p>None</p>', '<p>All</p>', '<p>Skip</p>', '', '', '', '', '', 'A', 1639502142, 1639502142),
(9, 4, 6, 5, '', '', '<p><span xss=removed>What is the first step in the software development lifecycle?</span><br></p>', 'System Design', '<p>C<span xss=removed>oding</span></p>', '<p>System Testing</p>', '<p><span xss=removed>Preliminary Investigation and Analysis</span><br></p>', '<p>None</p>', '', '', '', '', '', 'D', 1639555791, 1639555791),
(10, 4, 6, 5, '', '', '<p><span xss=removed>What does the study of an existing system refer to?</span><br></p>', '<p>Details of DFD</p>', '<p>Feasibility Study</p>', '<p>System Analysis</p>', '<p>System Planning</p>', '<p>All</p>', '', '', '', '', '', 'C', 1639555845, 1639555845),
(11, 4, 6, 5, '', '', '<p><span xss=removed>Model selection is based on __________.</span><br></p>', '<p>Requirements</p>', '<p>Development teams and users</p>', '<p>Project type and risk</p>', '<p>All of the above</p>', '<p>None</p>', '', '', '', '', '', 'D', 1639555920, 1639555920),
(12, 5, 1, 5, '', '', '<p><span xss=removed>Which of the following is generally used for performing tasks like creating the structure of the relations, deleting relation?</span><br></p>', '<p xss=removed>DML(Data Manipulation Language)</p>', '<p>Query</p>', '<p>Relational Schema</p>', '<p>DDL(Data Definition Language)</p>', '<p>None</p>', '', '', '', '', '', 'D', 1639847266, 1639847266),
(13, 5, 1, 5, '', '', '<p><span xss=removed>Which one of the following given statements possibly contains the error?</span><br></p>', '<p><span xss=removed>select * from emp where empid = 10003;</span><br></p>', '<p><span xss=removed>select empid from emp where empid = 10006;</span><br></p>', '<p><span xss=removed>select empid from emp;</span><br></p>', '<p><span xss=removed>select empid where empid = 1009 and Lastname = \'GELLER\';</span><br></p>', '<p>select * from emp WHERE empname = \"NONE\";</p>', '', '', '', '', '', 'D', 1639847413, 1639847413),
(14, 5, 1, 5, '', '', '<p><span xss=removed>What do you mean by one to many relationships?</span><br></p>', '<p><span xss=removed>One class may have many teachers</span><br></p>', '<p><span xss=removed>One teacher can have many classes</span><br></p>', '<p><span xss=removed>Many classes may have many teachers</span><br></p>', '<p><span xss=removed>Many teachers may have many classes</span><br></p>', '<p>All of above</p>', '', '', '', '', '', 'B', 1639847466, 1639847466),
(15, 5, 1, 5, '', '', '<p><span xss=removed>The term \"FAT\" is stands for?</span><br></p>', '<p><span xss=removed>File Allocation Tree</span><br></p>', '<p xss=removed>File Allocation Table</p>', '<p>File Allocation Graph</p>', '<p>All of above</p>', '<p>None of above</p>', '', '', '', '', '', 'B', 1639847554, 1639847554),
(16, 5, 1, 5, '', '', '<p><span xss=removed>Which of the following can be considered as the maximum size that is supported by FAT?</span><br></p>', '<p>8 GB</p>', '<p>4 GB</p>', '<p>512 GB</p>', '<p>4 TB</p>', '<p>None of above</p>', '', '', '', '', '', 'B', 1639847621, 1639847621),
(17, 6, 7, 5, '', '', '<p>Demo Ques?</p>', '<p>Choose Here</p>', '<p>Choose Here 2</p>', '<p>Choose Here 3</p>', '<p>Choose Here 4</p>', '<p>None</p>', '', '', '', '', '', 'A', 1640061233, 1640061233),
(18, 6, 7, 5, '', '', '<p>Demo Ques 2?</p>', '<p>Sample</p>', '<p>Sample 2</p>', '<p>Sample 3</p>', '<p>Sample 4</p>', '<p>All of Above</p>', '', '', '', '', '', 'C', 1640061280, 1640061280),
(19, 6, 7, 5, '', '', '<p>Sample Ques 3?</p>', '<p>Demo 1</p>', '<p>Demo 2</p>', '<p>Demo 3</p>', '<p>Demo 4</p>', '<p>All of Above</p>', '', '', '', '', '', 'D', 1640061313, 1640061313),
(20, 6, 7, 5, '', '', '<p>Ques 4? Ques 4?</p>', 'Sample Ans 1', '<p>Sample Ans 2</p>', '<p>Sample Ans 3</p>', '<p>Sample Ans 4</p>', '<p>Sample Ans 5</p>', '', '', '', '', '', 'E', 1640061360, 1640061360);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) DEFAULT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'Administrator', '$2y$12$JjM5xBghu01DOMBL4./8M.V54I2CIuLNqQ1dHTRPbnHCprQRa3FKq', 'admin@mail.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1640158309, 1, 'Admin', 'Williams', 'ADMIN', '0'),
(3, '::1', '12183018', '$2y$10$TLtlU8WsPUBQgLWcL5n8SO9YoTd1jDktGIkIvm9Fk2ROI0yJQ.TlC', 'steeve@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1550225511, 1639556039, 1, 'Steeve', 'Jackman', NULL, NULL),
(4, '::1', '12345678', '$2y$10$9CxUKgrB/0tlgOEIec1Fl.RMrLLcpJPGyFqqRh2gec.crgeVBWvym', 'jeremy@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1550226286, 1550743600, 1, 'Jeremy', 'McCugh', NULL, NULL),
(8, '::1', '01234567', '$2y$10$5pAJAyB3XvrGEkvGak2QI.1pWqwK/S76r3Pf4ltQSGQzLMpw53Tvy', 'conrad@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1550289356, 1639555952, 1, 'Conrad', 'Wills', NULL, NULL),
(9, '::1', '01112004', '$2y$10$bjU.7aM7ZiVTqLh/SPwSeO0iMmDtX.HDlc22DUAiNjlVqbAtTGvA2', 'demostd@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1639498020, 1639843341, 1, 'Demo', 'Student', NULL, NULL),
(10, '::1', '24000011', '$2y$10$OcwaL9G18Z62JLSgAWBBW.wP1DS0m0eNa8yHGKf5P4xhu97VJJBzO', 'danny@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1639498362, 1639845648, 1, 'Danny', 'Test', NULL, NULL),
(11, '::1', '88888888', '$2y$10$Hzz3dl6vSQnrve6ZglW/1OFqX0FlUn0MtvnkRBQ0B9EaoKvNJGRsi', 'thomas@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1639670971, 1639929460, 1, 'Thomas', 'Thomas', NULL, NULL),
(12, '::1', '77777777', '$2y$10$OdDzP0IF2JwOLoOsBgFj3.GN.viBu2wmF5hQCk0PGbdxmeYgBsrtS', 'demolecturer@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1640061049, 1640158156, 1, 'Demo', 'Lecturer', NULL, NULL),
(13, '::1', '1111111111', '$2y$10$gRsfuWMe6ina/FSQL76OLetEat9r6qIHkqLa5W4kVhuzH9963hNk2', 'teststudent@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1640061112, 1640157668, 1, 'Test', 'Student', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(3, 1, 1),
(5, 3, 3),
(6, 4, 2),
(10, 8, 2),
(11, 9, 3),
(12, 10, 2),
(13, 11, 2),
(14, 12, 2),
(15, 13, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lecturer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_history`
--
ALTER TABLE `exam_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `department_course`
--
ALTER TABLE `department_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `lecturer_class`
--
ALTER TABLE `lecturer_class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `student_number` (`student_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`exam_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `tb_question`
--
ALTER TABLE `tb_question`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`),
  ADD UNIQUE KEY `uc_email` (`email`) USING BTREE;

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lecturer`
--
ALTER TABLE `lecturer`
  MODIFY `lecturer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `exam_history`
--
ALTER TABLE `exam_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `department_course`
--
ALTER TABLE `department_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `lecturer_class`
--
ALTER TABLE `lecturer_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tb_question`
--
ALTER TABLE `tb_question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD CONSTRAINT `lecturer_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `exam_history`
--
ALTER TABLE `exam_history`
  ADD CONSTRAINT `exam_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`exam_id`),
  ADD CONSTRAINT `exam_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `department_course`
--
ALTER TABLE `department_course`
  ADD CONSTRAINT `department_course_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`),
  ADD CONSTRAINT `department_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `lecturer_class`
--
ALTER TABLE `lecturer_class`
  ADD CONSTRAINT `lecturer_class_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`),
  ADD CONSTRAINT `lecturer_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `exam_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`),
  ADD CONSTRAINT `exam_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `tb_question`
--
ALTER TABLE `tb_question`
  ADD CONSTRAINT `tb_question_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`),
  ADD CONSTRAINT `tb_question_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`);

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
