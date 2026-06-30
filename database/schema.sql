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

CREATE TABLE teachers (
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

CREATE TABLE grades (
  id INT AUTO_INCREMENT,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  grade DECIMAL(3, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY fk_student_id (student_id),
  KEY fk_course_id (course_id),
  CONSTRAINT fk_student_id FOREIGN KEY (student_id) REFERENCES students (id),
  CONSTRAINT fk_course_id FOREIGN KEY (course_id) REFERENCES courses (id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO students (name, email)
VALUES ('John Doe', 'john@example.com'),
       ('Jane Doe', 'jane@example.com');

INSERT INTO teachers (name, email)
VALUES ('Teacher 1', 'teacher1@example.com'),
       ('Teacher 2', 'teacher2@example.com');

INSERT INTO courses (name, description)
VALUES ('Course 1', 'This is course 1'),
       ('Course 2', 'This is course 2');

INSERT INTO grades (student_id, course_id, grade)
VALUES (1, 1, 85.00),
       (1, 2, 90.00),
       (2, 1, 78.00),
       (2, 2, 92.00);