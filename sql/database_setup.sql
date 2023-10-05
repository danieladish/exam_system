-- Create the database
CREATE DATABASE IF NOT EXISTS exam_system_db;

-- Switch to the database
USE exam_system_db;

CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(12) NOT NULL,
  user_type ENUM('teacher', 'student') NOT NULL
);
CREATE TABLE exams (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(100) NOT NULL,
  duration INT NOT NULL
);
-- Refer teacher to exams --
ALTER TABLE exams ADD COLUMN teacher_id INT;
ALTER TABLE exams ADD FOREIGN KEY (teacher_id) REFERENCES users(id);

CREATE TABLE questions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  exam_id INT,
  question VARCHAR(255) NOT NULL,
  answer_1 VARCHAR(255) NOT NULL,
  answer_2 VARCHAR(255) NOT NULL,
  answer_3 VARCHAR(255) NOT NULL,
  answer_4 VARCHAR(255) NOT NULL,
  correct_answer INT NOT NULL,
  FOREIGN KEY (exam_id) REFERENCES exams(id)
);

CREATE TABLE exam_scores (
  id INT PRIMARY KEY AUTO_INCREMENT,
  exam_id INT,
  student_id INT,
  score INT NOT NULL,
  FOREIGN KEY (exam_id) REFERENCES exams(id),
  FOREIGN KEY (student_id) REFERENCES users(id)
);

-- Insert a teacher
INSERT INTO users (username, password, user_type) VALUES ('teacher1', 'password123', 'teacher');

-- Insert a student
INSERT INTO users (username, password, user_type) VALUES ('student1', 'password456', 'student');

-- Insert an exam created by the teacher
INSERT INTO exams (title, duration, teacher_id) VALUES ('Math Exam', 60, 1);

-- Insert an exam created by the teacher
INSERT INTO exams (title, duration, teacher_id) VALUES ('Geography Test', 3, 1);

-- Get the ID of the last inserted exam
SET @exam_id = LAST_INSERT_ID();

-- Insert questions for the Geography test
INSERT INTO questions (exam_id, question, answer_1, answer_2, answer_3, answer_4, correct_answer)
VALUES
  (@exam_id, 'Which is the capital of Portugal?', 'Madrid', 'Rome', 'Lisbon', 'Athens', 3),
  (@exam_id, 'Which is the capital of Germany?', 'Berlin', 'Vienna', 'Paris', 'Warsaw', 1),
  (@exam_id, 'Which is the capital of Norway?', 'Helsinki', 'Oslo', 'Stockholm', 'Copenhagen', 2);

-- Insert an exam created by the teacher
INSERT INTO exams (title, duration, teacher_id) VALUES ('English Grammar Test', 10, 1);

-- Get the ID of the last inserted exam
SET @exam_id = LAST_INSERT_ID();

-- Insert questions for the English Grammar test
INSERT INTO questions (exam_id, question, answer_1, answer_2, answer_3, answer_4, correct_answer)
VALUES
  (@exam_id, 'Which is the correct sentence?', 'She goes to the store yesterday.', 'She go to the store yesterday.', 'She went to the store yesterday.', 'She gone to the store yesterday.', 3),
  (@exam_id, 'Which is the correct plural form of "child"?', 'childs', 'childes', 'child''s', 'children', 4),
  (@exam_id, 'Which is the correct form of the verb "to be" in the present tense for the pronoun "I"?', 'am', 'is', 'are', 'be', 1),
  (@exam_id, 'Which is the correct sentence?', 'I have went to the party last night.', 'I has gone to the party last night.', 'I went to the party last night.', 'I go to the party last night.', 3);
-- Insert an exam created by the teacher
INSERT INTO exams (title, duration, teacher_id) VALUES ('Science Test', 12, 1);

-- Get the ID of the last inserted exam
SET @exam_id = LAST_INSERT_ID();

-- Insert questions for the Science test
INSERT INTO questions (exam_id, question, answer_1, answer_2, answer_3, answer_4, correct_answer)
VALUES
  (@exam_id, 'What is the chemical symbol for oxygen?', 'O', 'H', 'C', 'Na', 1),
  (@exam_id, 'Which planet is known as the Red Planet?', 'Venus', 'Mars', 'Jupiter', 'Saturn', 2),
  (@exam_id, 'What is the unit of electric current?', 'Volt', 'Ampere', 'Ohm', 'Watt', 2),
  (@exam_id, 'What is the process by which plants make their own food?', 'Photosynthesis', 'Respiration', 'Digestion', 'Transpiration', 1);

-- Insert a score of 6 for student1 in the math exam
INSERT INTO exam_scores (exam_id, student_id, score) VALUES (1, 2, 6);
