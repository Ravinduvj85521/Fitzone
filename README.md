
# FitZone Fitness Center Web Application

A complete, dynamic website built for FitZone Fitness Center, a gym based in Kurunegala. This web application streamlines gym operations by providing an online platform for members to manage their activities and for staff to handle administration.

🚀 Features

 For Members & Guests
*   User Registration & Authentication: Secure sign-up and login system.
*   Class Schedule: View an interactive weekly schedule of fitness classes (Yoga, Cardio, Strength, etc.).
*   Class Booking: Book available classes with real-time capacity checks.
*   Membership Information: Browse clear and transparent pricing plans.
*   Contact Form: Submit inquiries directly to the management.

 For Administrators
*   Admin Dashboard: A secure, role-based control panel.
*   Query Management: View and respond to customer questions submitted through the website.
*   Booking Management: Monitor and manage all class bookings.

 Technical Features
*   Fully Responsive Design: Works seamlessly on desktop, tablet, and mobile devices.
*   Secure: Implements password hashing and SQL injection prevention.
*   Clean UI/UX: Modern, user-friendly interface built with a focus on simplicity.

🛠️ Built With

*   Frontend: HTML5, CSS3, JavaScript
*   Backend: PHP
*   Database: MySQL
*   Server: WAMP (Apache)

📦 Installation & Setup

To run this project locally:

1.  Prerequisites: Ensure you have a web server with PHP and MySQL (like XAMPP or WAMP) installed.
2.  Clone the Repository:
    ```bash
    git clone https://github.com/your-username/fitzone-fitness.git
    ```
3.  Setup Database:
    *   Create a MySQL database named `fitzone`.
    *   Open the database_tables.txt file, copy all its contents, and paste it into the SQL command window of your database tool to run it
);
4.  Configure Connection:
    *   Update the database credentials in `includes/config.php` with your local MySQL username and password.
5.  Run the Application:
    *   Move the project folder to your web server's root directory (e.g., `htdocs` for XAMPP).
    *   Open your browser and navigate to `http://localhost/fitzone-fitness`.

📁 Project Structure

fitzone/
├── assets/
│   ├── css/
│   │   ├── style.css (main styles)
│   │   ├── dashboard.css
│   │   └── classes.css
│   ├── js/
│   │   ├── main.js (common functions)
│   │   ├── booking.js
│   │   └── validation.js
│   └── images/ (all website images)
│
├── includes/
│   ├── config.php (database connection)
│   ├── header.php
│   ├── footer.php
│   ├── auth.php (authentication functions)
│   └── functions.php (helper functions)
│
├── api/ (all AJAX endpoints)
│   ├── get_classes.php
│   ├── book_class.php
│   └── check_availability.php
│
├── admin/ (admin panel)
│   ├── dashboard.php
│   ├── members.php
│   ├── classes.php
│   └── trainers.php
│
├── members/ (user portal)
│   ├── dashboard.php
│   ├── booking.php
│   ├── profile.php
│   └── payments.php
│
├── index.php (homepage)
├── about.php
├── classes.php
├── trainers.php
├── membership.php
├── blog.php
├── contact.php
├── login.php
├── register.php
└── 404.php

👥 Default Login Credentials

*   Member Login: Register a new account through the `register.php` page.
*   Admin Login: You need to manually set a user's role to `admin` in the database `users` table.

📄 License

This project was developed as a university assignment.
