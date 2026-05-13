# 📚 YIC Library System

**Course:** CS381 — Web Application Development  
**College:** Yanbu Industrial College  

---

## 🚀 Setup Instructions

1. **Project location:**
   ```
   D:\laragon\www\LibrarySystem1\
   ```

2. **Import the database:**
   - Open `http://localhost/phpmyadmin`
   - Click **Import**
   - Select the file: `D:\laragon\www\LibrarySystem1\sql\library_db.sql`
   - Click **Go**

3. **Open the project in your browser:**
   ```
   http://localhost/LibrarySystem1/
   ```

---

## 🔑 Login Credentials

| Role    | Email              | Password    |
|---------|--------------------|-------------|
| Admin   | admin@yic.edu.sa   | password123 |
| Student | danah@gmail.com    | password123 |
| Student | lamar@gmail.com    | password123 |
| Student | refal@gmail.com    | password123 |

---

## ✅ Features

**Student:** Register · Login / Logout · Browse books · Search · Borrow · View history · Return books

**Admin:** Login / Logout · Dashboard with live stats · View recent transactions · Manage books · Delete books

---

## 🔒 Security

- PDO Prepared Statements — SQL injection prevention
- `htmlspecialchars()` on all output — XSS prevention
- Server-side and client-side input validation
- `session_regenerate_id()` on login — session fixation prevention
- Role-based access control (admin / student)
- `password_hash()` and `password_verify()` for secure passwords

---

## 📁 File Structure

```
LibrarySystem1/
├── index.php
├── README.md
├── includes/
│   └── config.php
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── student/
│   ├── browse.php
│   └── history.php
├── admin/
│   ├── dashboard.php
│   ├── manage_books.php
│   └── delete_book.php
├── actions/
│   ├── borrow.php
│   └── return.php
├── assets/
│   ├── css/style.css
│   └── js/script.js
└── sql/
    └── library_db.sql
```
