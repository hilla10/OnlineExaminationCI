# 📝 Online Examination System

This Online Examination System is developed using **CodeIgniter**, **PHP**, **MySQL**, **HTML**, **CSS**, and **Bootstrap**. It provides a user‑friendly and secure platform to manage and conduct online exams for different user roles: **Administrators**, **Teachers**, and **Students**.

---

## 📚 Table of Contents

* [Setup](#setup)
* [Database](#database)
* [Directory Structure](#directory-structure)
* [Running the System](#running-the-system)
* [Login](#login)
* [Available Features](#available-features)
* [Troubleshooting](#troubleshooting)
* [Usage](#usage)
* [Security](#security)
* [Author](#author)
* [License](#license)

---

## ⚙️ Setup

### Requirements

* Web server (e.g., Apache)
* PHP ≥ 7.4
* MySQL Server
* CodeIgniter Framework (included)

### Installation

1. **Download** or **clone** the repository.
2. **Extract** the files and rename the folder to `OnlineExaminationCI`.
3. Move the folder to your server root directory (e.g., `htdocs` for XAMPP).
4. Start **Apache** and **MySQL** from your control panel.

---

## 🗃️ Database

1. Create a new database named `onlineexaminationci`.
2. Import the SQL file located at:

   ```bash
   /OnlineExaminationCI/DATABASE/onlineexaminationCi.sql
   ```

This will create the necessary tables and insert sample data.

---

## 🧱 Directory Structure

```text
OnlineExaminationCI/
│
├── assets/          # CSS, JavaScript, and images
├── application/     # MVC structure: controllers, models, views
├── database/        # SQL dump
├── system/          # CodeIgniter system core
├── index.php        # Front controller
└── .htaccess        # Apache URL configuration
```

---

## 🚀 Running the System

Deploy the system on your web server, then open a browser and navigate to:

```text
http://localhost/OnlineExaminationCI/
```

---

## 🔐 Login

### Admin Credentials

* **Email:** `admin@mail.com`
* **Password:** `Password@123`

> You can also log in with a Google account if OAuth is configured.

---

## ✅ Available Features

### 👩‍🏫 Teacher Panel

* Manage own exams
* Upload questions
* Monitor results

### 👨‍🎓 Student Panel

* View and take exams
* Review results
* Download result PDFs

### 🛠️ Admin Panel

* Manage users, departments, courses, and classes
* Assign course–department–teacher relationships
* Monitor the entire system

### General

* Exam scheduling with token‑code access
* Result management and export (PDF)
* Google login integration

---

## ❓ Troubleshooting

* Verify that Apache and MySQL services are running.
* Check database credentials in `application/config/database.php`.
* Ensure the base URL is correctly set in `application/config/config.php`.
* Inspect browser console and server logs for detailed errors.

---

## 💡 Usage

1. Log in using valid credentials.
2. Use the dashboard corresponding to your role.
3. Manage data (students, teachers, exams).
4. Students take exams and view/download results.

---

## 🔒 Security

* Use strong, unique passwords.
* Keep all frameworks and libraries up to date.
* Enforce role‑based access control.
* Sanitize all user inputs and use prepared statements.

---

## 🧑‍💻 Author

Developed by **Hailemichael Negusse**
Entoto Polytechnic College – Capstone Project

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
