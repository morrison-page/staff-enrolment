-- Insert mock data into user_details table
INSERT INTO user_details (user_id, email, password, salt, first_name, last_name, job_title, access_level, login_attempts, last_login_attempt)
VALUES
    (CONCAT('USER-', UUID()), 'WS344889@weston.ac.uk', '$argon2id$v=19$m=262144,t=12,p=4$T1E0QmpxN3JDQ0c0c1l3eA$zJEAXiPg+mWvBA5ZvjHb+Xf7tMK5+6y+0PdGRn7aG4I', '?�q�8�0�{?�����', 'Morrison', 'Page', 'System Administrator', 'admin', 0, NULL);
    (CONCAT('USER-', UUID()), 'john.doe@example.com', 'password', '', 'John', 'Doe', 'Developer', 'user', 0, NULL),

-- Insert mock data into course_details table
INSERT INTO course_details (course_id, course_title, course_date, course_duration, max_attendees, description)
VALUES
    (CONCAT('COURSE-', UUID()), 'Introduction to Python', '2024-11-13', 3, 25, 'Learn the basics of Python programming in this introductory course'),
    (CONCAT('COURSE-', UUID()), 'Data Science Basics', '2024-11-16', 2, 19, 'An overview of data science, covering key concepts and tools'),
    (CONCAT('COURSE-', UUID()), 'Advanced Machine Learning', '2024-12-04', 3, 38, 'Deep dive into machine learning algorithms and advanced techniques'),
    (CONCAT('COURSE-', UUID()), 'Cloud Computing Essentials', '2024-11-19', 3, 37, 'Explore the fundamentals of cloud computing and its applications'),
    (CONCAT('COURSE-', UUID()), 'Cybersecurity Fundamentals', '2024-12-03', 2, 11, 'Understand core cybersecurity principles to protect digital assets'),
    (CONCAT('COURSE-', UUID()), 'Web Development Bootcamp', '2024-11-30', 3, 14, 'Comprehensive web development course covering front-end and back-end'),
    (CONCAT('COURSE-', UUID()), 'AI Ethics and Society', '2024-11-15', 2, 12, 'Discuss ethical concerns in AI and its societal implications'),
    (CONCAT('COURSE-', UUID()), 'Blockchain Technology', '2024-11-23', 1, 45, 'Learn about blockchain, cryptocurrency, and decentralized systems'),
    (CONCAT('COURSE-', UUID()), 'Digital Marketing Strategy', '2024-12-10', 3, 20, 'Master digital marketing tactics for effective online campaigns'),
    (CONCAT('COURSE-', UUID()), 'Agile Project Management', '2024-11-22', 2, 36, 'An introduction to Agile methodologies in project management');


-- Insert mock data into enrolment_details table
INSERT INTO enrolment_details (enrollment_id, user_id, course_id, enrolled_date, status)
VALUES
    (CONCAT('ENROLL-', UUID()), (SELECT user_id FROM user_details WHERE email = 'john.doe@example.com'), (SELECT course_id FROM course_details WHERE course_title = 'Introduction to Programming'), CURRENT_DATE, 'enrolled'),
    (CONCAT('ENROLL-', UUID()), (SELECT user_id FROM user_details WHERE email = 'jane.smith@example.com'), (SELECT course_id FROM course_details WHERE course_title = 'Advanced Databases'), CURRENT_DATE, 'enrolled');

-- Insert mock data into course_attendance table
INSERT INTO course_attendance (attendance_id, course_id, current_enrollment_count)
VALUES
    (CONCAT('ATTEND-', UUID()), (SELECT course_id FROM course_details WHERE course_title = 'Introduction to Programming'), 1),
    (CONCAT('ATTEND-', UUID()), (SELECT course_id FROM course_details WHERE course_title = 'Advanced Databases'), 1);