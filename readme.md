## Online Examination System

This Online Examination System is developed using CodeIgniter, HTML, CSS, Bootstrap, PHP, and MySQL. It allows users to manage courses, departments, examinations, and user roles such as students, teachers, and administrators. The system provides a comprehensive platform for conducting and managing online exams efficiently.
Table of Contents

[Setup](#Setup)

[Database](#Database)

[Directory Structure](#Directory-Structure)

[Running the System](#Running-the-System)

[Login](#Login)

[Available Features](#Available-Features)

[Troubleshooting](#Troubleshooting)

[Usage](#Usage)

[Security](#Security)

## Setup

Before running the system, ensure you have the following prerequisites installed:

    Web server (e.g., Apache)
    PHP version (Recommended):	7.4 Above
    MySQL 
    CodeIgniter Framework

You can use WAMP, LAMP, MAMP, or XAMPP servers for deployment.

    Download the project files from GitHub.
    Extract the files and rename the folder to OnlineExaminationCI.
    Move the folder to your web server's root directory.

## Database

   1. Create a new database named `onlineexaminationci`.
   2. Import the `onlineexaminationCi.sql` file from localhost/OnlineExaminationCI/DATABASE/onlineexaminationCi.sql into the `onlineexaminationci` database to create the necessary tables and data.

## Directory-Structure

    assets/: CSS, JavaScript, and image assets.
    application/: CodeIgniter application files (controllers, models, views).
    database/: SQL dump for the database.
    system/: CodeIgniter core files.
    index.php: Entry point for the application.
    .htaccess: Configuration file for server settings.

## Running-the-System

    Deploy the system on a web server.
    Access the system using the appropriate URL in a web browser (e.g., http://localhost/OnlineExaminationCI/).

## Login

To access the system:

    Navigate to OnlineExaminationCI/index.php.
    Use the provided credentials or create new ones.
        Admin: 
        Email	: admin@mail.com
        Password: Password@123 
        OR 
        you can login using google account
        Click the "Login" button.

## Available-Features

    - Teacher Panel: Manage and oversee the exams conducted by teachers.
    - Student Panel: View and attend exams, and check exam results.
    - Administrator Panel: Manage users, courses, departments, and oversee system functionalities.
    Manage Course, Department, Class: Create and manage courses, departments, and classes.
    - Student Management: Add, update, and manage student information.
    - Teacher Management: Add, update, and manage teacher information.
    - Set Relations: Define and manage relationships between courses, departments, and teachers.
    - Set Questionnaires: Create and manage questionnaires for exams.
    - Conduct and Manage Examinations: Schedule and oversee examinations.
    - Examination Token Code: Generate and manage token codes for exams.
    - Attend Online Exam: Allow students to participate in online exams.
    - List Student’s Result: View and manage student results.
    - Download Result (PDF): Download exam results in PDF format.

## Troubleshooting

If you encounter issues:

    Ensure all required software and dependencies are installed.
    Double-check database connection settings in the configuration files.
    Review server logs for error messages or warnings.

## Usage

To use the system:

    Log in with appropriate credentials.
    Navigate through the dashboard to access various functionalities.
    Create, update, or delete courses, departments, and manage exams.
    Attend exams and review results.

## Security

When using the system, observe these security guidelines:

    Use strong, unique passwords for user accounts.
    Regularly update system components to address security vulnerabilities.
    Grant system access only to authorized personnel and enforce role-based user privileges.
