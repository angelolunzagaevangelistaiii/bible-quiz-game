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

CREATE TABLE IF NOT EXISTS scores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  score INT,
  total_questions INT,
  taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- questions (with category & difficulty)
CREATE TABLE IF NOT EXISTS questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  scripture_ref VARCHAR(120),
  question TEXT,
  option_a VARCHAR(255),
  option_b VARCHAR(255),
  option_c VARCHAR(255),
  option_d VARCHAR(255),
  correct CHAR(1) NOT NULL,
  category VARCHAR(100) DEFAULT 'Faith',
  difficulty ENUM('Easy','Medium','Hard') DEFAULT 'Easy'
);

-- per-question answers (tracking)
CREATE TABLE IF NOT EXISTS user_quiz_results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(255),
  user_email VARCHAR(255),
  user_id INT NULL,
  question_id INT,
  user_answer CHAR(1),
  is_correct TINYINT(1),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (question_id)
);

-- leaderboard (overall attempt)
CREATE TABLE IF NOT EXISTS leaderboard (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  score INT NOT NULL,
  category VARCHAR(100),
  difficulty VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin_users (name,email,password)
VALUES ('Administrator', 'admin@example.com', '$2y$10$Ap2l0dx0uSuaoyWSQn2ToOkyp6UpH9x0LulzjhQYU7lnsrQ/szDXC');

-- sample user (password '123456')
INSERT INTO users (name,email,password) VALUES
('Test User','test@example.com','$2y$10$Ap2l0dx0uSuaoyWSQn2ToOkyp6UpH9x0LulzjhQYU7lnsrQ/szDXC');

-- sample questions
INSERT INTO questions (scripture_ref, question, option_a, option_b, option_c, option_d, correct, category, difficulty) VALUES
('John 3:16','What is the key phrase of John 3:16?','For God so loved the world','Judge the world','Remain silent forever','Only angels see it','A','Gospels','Easy'),
('Genesis 1:1','How does Genesis 1:1 begin?','In the beginning God created the heavens and the earth','God created humans first','God rested','God spoke to Moses','A','Faith','Easy'),
('Psalm 23:1','Psalm 23 starts with which phrase?','The Lord is my shepherd','The Lord is my king','The Lord is my judge','The Lord is my light','A','Wisdom','Easy'),
('Matthew 28:19','What are followers commanded to do in Matthew 28:19?','Make disciples of all nations','Pray only on Sundays','Hide your faith','Leave the city','A','Gospels','Medium'),
('Revelation 21:4','What will YHVH do in Revelation 21:4?','Wipe away every tear','Send every tear back','Make tears multiply','Teach people to ignore pain','A','Prophecy','Medium');
