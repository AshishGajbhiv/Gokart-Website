# 🏎️ Go-Kart Event Registration System – S & D Motorsports

A full-stack web application built to manage go-kart racing events, team registrations, admin approvals, and automated email notifications.

This project is developed for real-world usage by a motorsports event organizer.

---

## 🚀 Features

### 👥 User Side
- View upcoming go-kart events
- Register for specific events only (no general registration)
- Email OTP verification before registration
- Upload team logo and payment screenshot
- Track registration status:
  - ⏳ Pending
  - ✅ Approved
  - ❌ Rejected (with reason)

### 🛠️ Admin Panel
- Secure admin login
- Create, edit, and manage events
- Open / Close event registrations
- View registrations event-wise
- Approve or reject registrations with reason
- Send automatic approval/rejection emails
- Export registrations as CSV (event-wise)
- Publish scrolling notices on homepage

---

## 🧑‍💻 Tech Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP (Core PHP)
- **Database:** MySQL
- **Email:** PHPMailer (SMTP)
- **Server:** Apache (XAMPP / Hosting server)

---

## 🔐 Security Notes (IMPORTANT)

This repository **does NOT contain sensitive information**.

The following files and folders are intentionally excluded using `.gitignore`:

- `includes/config.php`  
  (Contains SMTP email credentials)
- `uploads/`  
  (User uploaded files like images and payment screenshots)

### 🔧 To run this project locally:
You must create your own `includes/config.php` file with:

```php
<?php
define('SMTP_EMAIL', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
