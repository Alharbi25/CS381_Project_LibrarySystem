-- ============================================
-- YIC Library System — library_db.sql
-- Import once in phpMyAdmin
-- ============================================

CREATE DATABASE IF NOT EXISTS library_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE library_db;

DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS users;

-- ============================================
-- users  (1 admin + 10 students = 11 rows)
-- All passwords = password123
-- ============================================
CREATE TABLE users (
    id       INT          NOT NULL AUTO_INCREMENT,
    username VARCHAR(50)  NOT NULL,
    email    VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role     ENUM('admin','student') DEFAULT 'student',
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, email, password, role) VALUES
('Aryam Admin', 'admin@yic.edu.sa', '$2y$10$X8U1lb3HJiCuoukZxLXgNOjmVtBVNzyabpKAGbRJHq2FJfrYnSOI2', 'admin'),
('Danah Ali',   'danah@gmail.com',  '$2y$10$PVh7WLTyxia5Dcr5sfseq.cmTdrWb9IeS7cmjzjzNZpb.SPpTBJlS', 'student'),
('Lamar Salem', 'lamar@gmail.com',  '$2y$10$qwMSwRJH.eGLQVoF/jXGZuKZSSXDNKOglfAC9Rd6xkcmLr.BlVFG2', 'student'),
('Rama Khalid', 'rama@yic.edu.sa',  '$2y$10$k4gqN4Nkp1CLJjs0EaIiluBhfKnNPRu9iFdUgeDB6aQRFABFQW7Z.', 'student'),
('Refal Ahmed', 'refal@gmail.com',  '$2y$10$LSDIp3qiLz5SO.DlE4m.5uXYYWeA9cv6m4QkyL6tLiGaVdXRkW2Mq', 'student'),
('Sara Nasser', 'sara@yic.edu.sa',  '$2y$10$X8U1lb3HJiCuoukZxLXgNOjmVtBVNzyabpKAGbRJHq2FJfrYnSOI2', 'student'),
('Nora Hassan', 'nora@yic.edu.sa',  '$2y$10$X8U1lb3HJiCuoukZxLXgNOjmVtBVNzyabpKAGbRJHq2FJfrYnSOI2', 'student'),
('Huda Omar',   'huda@gmail.com',   '$2y$10$X8U1lb3HJiCuoukZxLXgNOjmVtBVNzyabpKAGbRJHq2FJfrYnSOI2', 'student'),
('Maha Saad',   'maha@gmail.com',   '$2y$10$X8U1lb3HJiCuoukZxLXgNOjmVtBVNzyabpKAGbRJHq2FJfrYnSOI2', 'student'),
('Lina Turki',  'lina@yic.edu.sa',  '$2y$10$X8U1lb3HJiCuoukZxLXgNOjmVtBVNzyabpKAGbRJHq2FJfrYnSOI2', 'student'),
('Reem Fahad',  'reem@gmail.com',   '$2y$10$X8U1lb3HJiCuoukZxLXgNOjmVtBVNzyabpKAGbRJHq2FJfrYnSOI2', 'student');

-- ============================================
-- books  (11 rows)
-- ============================================
CREATE TABLE books (
    id       INT          NOT NULL AUTO_INCREMENT,
    title    VARCHAR(255) NOT NULL,
    author   VARCHAR(100) NOT NULL,
    category VARCHAR(50)  DEFAULT NULL,
    status   ENUM('available','borrowed') DEFAULT 'available',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO books (title, author, category, status) VALUES
('HTML5 Guide',               'John Doe',          'Web',        'available'),
('Mastering CSS',             'Jane Smith',         'Design',     'borrowed'),
('PHP for Beginners',         'Mark Ott',           'Backend',    'available'),
('MySQL Secrets',             'Sara Lee',           'Database',   'available'),
('JS Interactive',            'Tom Cook',           'JavaScript', 'available'),
('Cyber Security Basics',     'Alan Turing',        'Security',   'borrowed'),
('AI Fundamentals',           'Ian Goodfellow',     'AI',         'available'),
('Cloud Computing',           'Bill Gates',         'Technology', 'available'),
('Data Science 101',          'Python Expert',      'Data',       'available'),
('Digital Ethics',            'Phil Smith',         'Philosophy', 'available'),
('A History of Saudi Arabia', 'Madawi Al-Rasheed',  'History',    'available');

-- ============================================
-- transactions  (10 rows)
-- ============================================
CREATE TABLE transactions (
    id          INT           NOT NULL AUTO_INCREMENT,
    user_id     INT           NOT NULL,
    book_id     INT           NOT NULL,
    borrow_date DATE          NOT NULL,
    return_date DATE          DEFAULT NULL,
    fine_amount DECIMAL(10,2) DEFAULT '0.00',
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY book_id (book_id),
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users (id),
    CONSTRAINT fk_book FOREIGN KEY (book_id) REFERENCES books (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO transactions (user_id, book_id, borrow_date, return_date, fine_amount) VALUES
(2,  2,  '2026-04-01', '2026-04-10', 0.00),
(2,  6,  '2026-04-05', '2026-04-15', 0.00),
(3,  1,  '2026-04-08', '2026-04-18', 0.00),
(4,  3,  '2026-04-10', NULL,         5.00),
(5,  4,  '2026-04-12', '2026-04-20', 0.00),
(6,  5,  '2026-04-14', NULL,         0.00),
(7,  7,  '2026-04-16', '2026-04-25', 0.00),
(8,  8,  '2026-04-18', NULL,         2.50),
(9,  2,  '2026-04-20', NULL,         0.00),
(10, 6,  '2026-04-22', NULL,         0.00);
