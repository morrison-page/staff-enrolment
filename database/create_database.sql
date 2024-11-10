-- Create Database
CREATE DATABASE IF NOT EXISTS ws344889_wad;
USE ws344889_wad;

-- Create user_details table
CREATE TABLE user_details (
    user_id CHAR(41) PRIMARY KEY DEFAULT (CONCAT('USER-', UUID())),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    salt BINARY(16) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    job_title VARCHAR(100),
    access_level ENUM('admin', 'user') NOT NULL,
    login_attempts INT DEFAULT 0,
    last_login_attempt TIMESTAMP
);

-- Create course_details table
CREATE TABLE course_details (
    course_id CHAR(43) PRIMARY KEY DEFAULT (CONCAT('COURSE-', UUID())),
    course_title VARCHAR(255) NOT NULL,
    course_date DATE NOT NULL,
    course_duration INT NOT NULL,
    max_attendees INT NOT NULL,
    description TEXT
);

-- Create enrollment_details table
CREATE TABLE enrolment_details (
    enrollment_id CHAR(43) PRIMARY KEY DEFAULT (CONCAT('ENROLL-', UUID())),
    user_id CHAR(41) NOT NULL,
    course_id CHAR(43) NOT NULL,
    enrolled_date DATE DEFAULT CURRENT_DATE,
    status ENUM('enrolled', 'completed', 'canceled') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user_details(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES course_details(course_id) ON DELETE CASCADE
);

-- Create course_attendance table
CREATE TABLE course_attendance (
    attendance_id CHAR(43) PRIMARY KEY DEFAULT (CONCAT('ATTEND-', UUID())),
    course_id CHAR(43) NOT NULL,
    current_enrollment_count INT DEFAULT 0,
    FOREIGN KEY (course_id) REFERENCES course_details(course_id) ON DELETE CASCADE
);
