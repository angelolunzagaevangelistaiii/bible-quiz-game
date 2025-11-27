-- Create database and tables for Bible Quiz Game
CREATE DATABASE IF NOT EXISTS bible_quiz_game;
USE bible_quiz_game;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(150) UNIQUE,
  password VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  scripture_ref VARCHAR(80),
  question TEXT,
  option_a VARCHAR(255),
  option_b VARCHAR(255),
  option_c VARCHAR(255),
  option_d VARCHAR(255),
  category VARCHAR(50) DEFAULT 'Faith',
  difficulty ENUM('Easy','Medium','Hard') DEFAULT 'Easy',
  correct CHAR(1) NOT NULL
);

CREATE TABLE IF NOT EXISTS scores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  score INT,
  total_questions INT,
  taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS user_quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    user_answer VARCHAR(1) NOT NULL,
    is_correct TINYINT(1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS leaderboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- sample user (password '123456')
INSERT INTO users (name,email,password) VALUES
('Test User','test@example.com','$2y$10$QmVdV1C7IGqN9dW9I5tz1e9O5.1pLX/xmOZbDxYOfpEiyrJSpO1fu');

-- sample questions
INSERT INTO questions (scripture_ref,question,option_a,option_b,option_c,option_d,correct) VALUES
('John 3:16','What is the core message of John 3:16?','God so loved the world','God is angry','God is distant','God is silent','A'),
('Genesis 1:1','How does Genesis 1:1 begin?','In the beginning God created the heavens and the earth','God created man first','In the end God created','God rested first','A'),
('Psalm 23:1','Psalm 23 opens with which phrase?','The Lord is my shepherd','The Lord is my king','The Lord is my judge','The Lord is my light','A');
