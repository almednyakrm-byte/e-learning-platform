CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE students (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE courses (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE subjects (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE teachers (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE student_courses (
  id INT AUTO_INCREMENT,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (student_id),
  KEY (course_id),
  CONSTRAINT fk_student_id FOREIGN KEY (student_id) REFERENCES students (id),
  CONSTRAINT fk_course_id FOREIGN KEY (course_id) REFERENCES courses (id)
);

CREATE TABLE course_subjects (
  id INT AUTO_INCREMENT,
  course_id INT NOT NULL,
  subject_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (course_id),
  KEY (subject_id),
  CONSTRAINT fk_course_id FOREIGN KEY (course_id) REFERENCES courses (id),
  CONSTRAINT fk_subject_id FOREIGN KEY (subject_id) REFERENCES subjects (id)
);

CREATE TABLE teacher_courses (
  id INT AUTO_INCREMENT,
  teacher_id INT NOT NULL,
  course_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (teacher_id),
  KEY (course_id),
  CONSTRAINT fk_teacher_id FOREIGN KEY (teacher_id) REFERENCES teachers (id),
  CONSTRAINT fk_course_id FOREIGN KEY (course_id) REFERENCES courses (id)
);

CREATE TABLE student_records (
  id INT AUTO_INCREMENT,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  grade DECIMAL(3, 2),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (student_id),
  KEY (course_id),
  CONSTRAINT fk_student_id FOREIGN KEY (student_id) REFERENCES students (id),
  CONSTRAINT fk_course_id FOREIGN KEY (course_id) REFERENCES courses (id)
);

INSERT INTO users (id, username, email, password, role)
VALUES
(1, 'admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO students (id, name, email)
VALUES
(1, 'Student 1', 'student1@example.com'),
(2, 'Student 2', 'student2@example.com');

INSERT INTO courses (id, name, description)
VALUES
(1, 'Course 1', 'This is course 1'),
(2, 'Course 2', 'This is course 2');

INSERT INTO subjects (id, name, description)
VALUES
(1, 'Subject 1', 'This is subject 1'),
(2, 'Subject 2', 'This is subject 2');

INSERT INTO teachers (id, name, email)
VALUES
(1, 'Teacher 1', 'teacher1@example.com'),
(2, 'Teacher 2', 'teacher2@example.com');

INSERT INTO student_courses (id, student_id, course_id)
VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1);

INSERT INTO course_subjects (id, course_id, subject_id)
VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1);

INSERT INTO teacher_courses (id, teacher_id, course_id)
VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1);

INSERT INTO student_records (id, student_id, course_id, grade)
VALUES
(1, 1, 1, 85.00),
(2, 1, 2, 90.00),
(3, 2, 1, 80.00);