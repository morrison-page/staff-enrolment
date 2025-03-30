-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 30, 2025 at 03:52 PM
-- Server version: 10.11.11-MariaDB-0+deb12u1
-- PHP Version: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ws344889_wad`
--

-- --------------------------------------------------------

--
-- Table structure for table `course_details`
--

CREATE TABLE `course_details` (
  `course_id` char(43) NOT NULL DEFAULT concat('COURSE-',uuid()),
  `course_title` varchar(255) NOT NULL,
  `course_date` date NOT NULL,
  `course_duration` int(11) NOT NULL,
  `max_attendees` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `course_details`
--

INSERT INTO `course_details` (`course_id`, `course_title`, `course_date`, `course_duration`, `max_attendees`, `description`) VALUES
('COURSE-4a91e5c7-a8ea-11ef-8c1a-005056031580', 'Introduction to Python', '2025-11-30', 6, 25, 'Learn the basics of Python programming in this introductory course'),
('COURSE-4a91e75b-a8ea-11ef-8c1a-005056031580', 'Data Science Basics', '2024-11-16', 2, 19, 'An overview of data science, covering key concepts and tools'),
('COURSE-4a91e78e-a8ea-11ef-8c1a-005056031580', 'Advanced Machine Learning', '2025-09-22', 3, 38, 'Deep dive into machine learning algorithms and advanced techniques'),
('COURSE-4a91e7b6-a8ea-11ef-8c1a-005056031580', 'Cloud Computing Essentials', '2025-11-19', 3, 37, 'Explore the fundamentals of cloud computing and its applications'),
('COURSE-4a91e7d1-a8ea-11ef-8c1a-005056031580', 'Cybersecurity Fundamentals', '2025-11-05', 2, 11, 'Understand core cybersecurity principles to protect digital assets'),
('COURSE-4a91e7ed-a8ea-11ef-8c1a-005056031580', 'Web Development Bootcamp', '2024-11-30', 3, 14, 'Comprehensive web development course covering front-end and back-end'),
('COURSE-4a91e809-a8ea-11ef-8c1a-005056031580', 'AI Ethics and Society', '2025-05-07', 2, 12, 'Discuss ethical concerns in AI and its societal implications'),
('COURSE-4a91e822-a8ea-11ef-8c1a-005056031580', 'Blockchain Technology', '2028-01-19', 1, 45, 'Learn about blockchain, cryptocurrency, and decentralized systems'),
('COURSE-4a91e83c-a8ea-11ef-8c1a-005056031580', 'Digital Marketing Strategy', '2025-06-30', 3, 20, 'Master digital marketing tactics for effective online campaigns'),
('COURSE-4a91e854-a8ea-11ef-8c1a-005056031580', 'Agile Project Management', '2026-08-20', 2, 2, 'An introduction to Agile methodologies in project management'),
('COURSE-e6e718f1-011b-11f0-8e70-005056031580', 'How to use Markdown!', '2025-03-27', 3, 26, 'Learn how to use markdown for efficient blogging and note taking');

-- --------------------------------------------------------

--
-- Table structure for table `enrolment_details`
--

CREATE TABLE `enrolment_details` (
  `enrolment_id` char(43) NOT NULL DEFAULT concat('ENROL-',uuid()),
  `user_id` char(41) NOT NULL,
  `course_id` char(43) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `enrolment_details`
--

INSERT INTO `enrolment_details` (`enrolment_id`, `user_id`, `course_id`) VALUES
('ENROL-3cbad5b4-b5aa-11ef-8c1a-005056031580', 'USER-0c58374d-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e75b-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbad6de-b5aa-11ef-8c1a-005056031580', 'USER-1949c099-b0ee-11ef-8c1a-005056031580', 'COURSE-4a91e78e-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbad753-b5aa-11ef-8c1a-005056031580', 'USER-26df9efe-b0ee-11ef-8c1a-005056031580', 'COURSE-4a91e7b6-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbad7ba-b5aa-11ef-8c1a-005056031580', 'USER-50593856-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e7d1-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbad824-b5aa-11ef-8c1a-005056031580', 'USER-784352e9-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e7ed-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbad8dd-b5aa-11ef-8c1a-005056031580', 'USER-8c874b85-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e822-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbad93f-b5aa-11ef-8c1a-005056031580', 'USER-9a3657e2-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e83c-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbad995-b5aa-11ef-8c1a-005056031580', 'USER-a61f9fa5-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e854-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbad9f0-b5aa-11ef-8c1a-005056031580', 'USER-b1b2f69c-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e5c7-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbadaba-b5aa-11ef-8c1a-005056031580', 'USER-e7dd45b6-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e78e-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbadb22-b5aa-11ef-8c1a-005056031580', 'USER-094d5db5-b0ee-11ef-8c1a-005056031580', 'COURSE-4a91e7b6-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbadb84-b5aa-11ef-8c1a-005056031580', 'USER-0c58374d-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e7d1-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbadbec-b5aa-11ef-8c1a-005056031580', 'USER-1949c099-b0ee-11ef-8c1a-005056031580', 'COURSE-4a91e7ed-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbadc4c-b5aa-11ef-8c1a-005056031580', 'USER-26df9efe-b0ee-11ef-8c1a-005056031580', 'COURSE-4a91e809-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbadcaf-b5aa-11ef-8c1a-005056031580', 'USER-50593856-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e822-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbadd1a-b5aa-11ef-8c1a-005056031580', 'USER-784352e9-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e83c-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbaddf4-b5aa-11ef-8c1a-005056031580', 'USER-8c874b85-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e5c7-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbade69-b5aa-11ef-8c1a-005056031580', 'USER-9a3657e2-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e75b-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbaded5-b5aa-11ef-8c1a-005056031580', 'USER-a61f9fa5-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e78e-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbadf47-b5aa-11ef-8c1a-005056031580', 'USER-b1b2f69c-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e7b6-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbae027-b5aa-11ef-8c1a-005056031580', 'USER-e7dd45b6-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e7ed-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbae097-b5aa-11ef-8c1a-005056031580', 'USER-094d5db5-b0ee-11ef-8c1a-005056031580', 'COURSE-4a91e809-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbae10a-b5aa-11ef-8c1a-005056031580', 'USER-0c58374d-b0ed-11ef-8c1a-005056031580', 'COURSE-4a91e822-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbae192-b5aa-11ef-8c1a-005056031580', 'USER-1949c099-b0ee-11ef-8c1a-005056031580', 'COURSE-4a91e83c-a8ea-11ef-8c1a-005056031580'),
('ENROL-3cbae208-b5aa-11ef-8c1a-005056031580', 'USER-26df9efe-b0ee-11ef-8c1a-005056031580', 'COURSE-4a91e854-a8ea-11ef-8c1a-005056031580'),
('ENROL-bf452d33-040e-11f0-85da-005056031580', 'USER-3aa43b3b-0d76-11f0-85da-005056031580', 'COURSE-4a91e7b6-a8ea-11ef-8c1a-005056031580'),
('ENROL-c00a64ed-040e-11f0-85da-005056031580', 'USER-3aa43b3b-0d76-11f0-85da-005056031580', 'COURSE-4a91e822-a8ea-11ef-8c1a-005056031580'),
('ENROL-c0919595-0d72-11f0-85da-005056031580', 'USER-aba0fa59-0d72-11f0-85da-005056031580', 'COURSE-4a91e5c7-a8ea-11ef-8c1a-005056031580'),
('ENROL-c1430c51-0d72-11f0-85da-005056031580', 'USER-aba0fa59-0d72-11f0-85da-005056031580', 'COURSE-4a91e822-a8ea-11ef-8c1a-005056031580'),
('ENROL-c27d42e2-0d72-11f0-85da-005056031580', 'USER-aba0fa59-0d72-11f0-85da-005056031580', 'COURSE-4a91e7b6-a8ea-11ef-8c1a-005056031580'),
('ENROL-d4c853e7-0d6d-11f0-85da-005056031580', 'USER-3aa43b3b-0d76-11f0-85da-005056031580', 'COURSE-4a91e5c7-a8ea-11ef-8c1a-005056031580'),
('ENROL-f41f7faf-0125-11f0-8e70-005056031580', 'USER-3aa43b3b-0d76-11f0-85da-005056031580', 'COURSE-4a91e75b-a8ea-11ef-8c1a-005056031580'),
('ENROL-fde40373-0125-11f0-8e70-005056031580', 'USER-3aa43b3b-0d76-11f0-85da-005056031580', 'COURSE-4a91e7ed-a8ea-11ef-8c1a-005056031580');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_id` char(41) NOT NULL DEFAULT concat('USER-',uuid()),
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` binary(16) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `access_level` enum('admin','user') NOT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `last_login_attempt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_id`, `email`, `password`, `salt`, `first_name`, `last_name`, `job_title`, `access_level`, `login_attempts`, `last_login_attempt`) VALUES
('USER-094d5db5-b0ee-11ef-8c1a-005056031580', 'michael.harris@finance.com', '$argon2id$v=19$m=262144,t=12,p=4$cGFWM0M3dWFCaE9sYVBLUg$1EH0vyRd9r82XGUV7OHH84qHPeoN/KK90t5apVwwt7I', 0xb77c0a8fd67b288a1b3edd95a099015a, 'Michael', 'Harris', 'Accountant', 'user', 0, NULL),
('USER-0c58374d-b0ed-11ef-8c1a-005056031580', 'john.doe@example.com', '$argon2id$v=19$m=262144,t=12,p=4$Y1g0Q0tBYlpDNGpSakRJSQ$xT29le8D5Xazx+7qqPo0Xz696TUGR0Jn7UV6rjKlxfQ', 0x1f65c7d8c279a87247fe501d44e51472, 'John', 'Doe', 'Developer', 'user', 0, NULL),
('USER-1949c099-b0ee-11ef-8c1a-005056031580', 'sarah.lee@techlabs.io', '$argon2id$v=19$m=262144,t=12,p=4$UnZFLmVySkEzZlVMRm14aA$2mlRnIgopn11mvPsk0/bIXkdyoxpmTTRl8mVWOB49D0', 0x419090fbbe8d49c7c84e506ec6f176a2, 'Sarah', 'Lee', 'UI/UX Designer', 'user', 2, '2024-11-28 15:20:00'),
('USER-26df9efe-b0ee-11ef-8c1a-005056031580', 'chris.evans@networking.biz', '$argon2id$v=19$m=262144,t=12,p=4$UDV5WlRsNEF5enBVLmpnRw$734pQPzY8IRCpn7KhkzMuJY1K8PWXx/GEn287ap319w', 0xa64436793967105e51414f2eb4d9b0a5, 'Chris', 'Evans', 'Network Engineer', 'user', 0, NULL),
('USER-3aa43b3b-0d76-11f0-85da-005056031580', 'admin@enrolment.platform', '$argon2id$v=19$m=262144,t=12,p=1$e5GNQ7i3Bq7QLXYau8nwTg$zEjneZQIGL5yydg40921o2xbmMkQhp5LWQ/9rp1LX/Q', 0x2c1af1bff6ea63f9862a9b794f077aac, 'Admin', 'Admin', 'Platform Admin', 'admin', 0, '2025-03-30 14:51:49'),
('USER-50593856-b0ed-11ef-8c1a-005056031580', 'jane.smith@example.com', '$argon2id$v=19$m=262144,t=12,p=4$cWtCcDdiWTBTbnJQdERESA$E7VpV1jPWssTwmryWO1icyB/Mtok7cDV7XpwhXgbR8A', 0xe67a7a471d66c567e8d3f465c15274f6, 'Jane', 'Smith', 'Project Manager', 'user', 1, '2024-11-29 14:32:00'),
('USER-784352e9-b0ed-11ef-8c1a-005056031580', 'sam.taylor@workplace.org', '$argon2id$v=19$m=262144,t=12,p=4$R29wcWRpU2hTeTZiSEVWQg$yXDufZqdT4ypuz/yXBydwoVOW9gUjvAxCbJeoM8fIuI', 0x3c8d355ed31e0ce5841c6fb0e50ebd8f, 'Sam', 'Taylor', 'Support Analyst', 'user', 0, NULL),
('USER-8c874b85-b0ed-11ef-8c1a-005056031580', 'emily.brown@company.com', '$argon2id$v=19$m=262144,t=12,p=4$N3pydTYvQTJnN3ZjaVZuag$NrTKDOHN5pgiR0iCAKWg0+ohM7U75X7Cz3FpvNrulM0', 0x65256368f76e77f7176cee4c57a4fd9b, 'Emily', 'Brown', 'Human Resources', 'user', 2, '2024-11-28 09:45:00'),
('USER-9a3657e2-b0ed-11ef-8c1a-005056031580', 'david.jones@startup.net', '$argon2id$v=19$m=262144,t=12,p=4$WFdKdWE4eUdvNnB2Ly5yUg$m+DuzcXxSbwdhi/2UZECFmPEc7s28TFu4pWZHM/iw+k', 0x703947612e2c51dc42e9214c94ab3500, 'David', 'Jones', 'Software Engineer', 'user', 0, NULL),
('USER-a61f9fa5-b0ed-11ef-8c1a-005056031580', 'linda.jackson@globalcorp.org', '$argon2id$v=19$m=262144,t=12,p=4$c2JreGttb2swWUNPT1JLNg$qAHwgRl1rEH8XSL/nX2sExu2Jl/mLEfjaXXqY8cJk5M', 0xccd1a03616636db03337d259190f0d0c, 'Linda', 'Jackson', 'Quality Assurance', 'user', 1, '2024-11-29 08:12:00'),
('USER-aba0fa59-0d72-11f0-85da-005056031580', 'user@enrolment.platform', '$argon2id$v=19$m=262144,t=12,p=4$L3VhdVlFLnFMQjd3MGFnNw$nyC6HfgANaZjgK8LXcna1Gveyhl564oFemNolisq5q0', 0xae833c6d667f6fb740d61c31c18e95d5, 'User', 'User', 'Platform User', 'user', 0, '2025-03-30 14:24:53'),
('USER-b1b2f69c-b0ed-11ef-8c1a-005056031580', 'robert.miller@agency.com', '$argon2id$v=19$m=262144,t=12,p=4$RWlNQzdkdjY2clRFMzZoNg$CRdqt3Oakw8OwTPtaOmQ55fZbB894WGh6oUzKPi3F7s', 0x18e7b94bff692ddc04a46b942a3e8408, 'Robert', 'Miller', 'Creative Director', 'user', 0, '2024-12-02 21:06:33'),
('USER-e7dd45b6-b0ed-11ef-8c1a-005056031580', 'anna.scott@research.org', '$argon2id$v=19$m=262144,t=12,p=4$dXpaS2tDZVhCQ0JCUEJlWA$gC0uPgep401FOiyK8r0w6FM+Y5S6eaFF2ga9e7bcLZo', 0xc4c784af78b08a823a1d491076f2c234, 'Anna', 'Scott', 'Data Scientist', 'user', 3, '2024-11-30 10:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course_details`
--
ALTER TABLE `course_details`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `enrolment_details`
--
ALTER TABLE `enrolment_details`
  ADD PRIMARY KEY (`enrolment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enrolment_details`
--
ALTER TABLE `enrolment_details`
  ADD CONSTRAINT `enrolment_details_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course_details` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrolment_details_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_details` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
