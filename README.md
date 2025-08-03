# Online Enrollment System

A comprehensive PHP-based online enrollment system for high schools that allows students to register online without the need for physical visits.

## 🎯 System Overview

**Title:** Online Enrollment System  
**Purpose:** Provides online access for students to enroll without walking in to the school  
**Language:** PHP with MySQL database  
**Users:** High school students and admin users

## ✨ Features

### 🏠 Student Dashboard
- **4 Colored Statistics Tiles:**
  - Enrolled students count (Blue)
  - Pending applicants count (Yellow)
  - Male students count (Light Blue)
  - Female students count (Green)
- **Class Schedule Table:** Shows subjects, times, days, and rooms
- **Recent Announcements:** Displayed at the bottom

### 📝 Student Registration Form
**Personal Information:**
- First Name, Last Name
- Date of Birth
- Gender (Male/Female)

**Contact Information:**
- Email Address
- Phone Number

**Enrollment Information:**
- Student ID (auto-generated)
- Grade Level (Grade 7-12)
- Program dropdown (STEM, ABM, HUMSS, GAS)

**Features:**
- ✅ Fully functional "Register" button
- ✅ Form validation (required fields, valid email)
- ✅ Creates new student record in database
- ✅ Dashboard counts auto-update
- ✅ Success confirmation page

### 🔧 Admin Features
- **Student Management:** Add, edit, delete students
- **Status Management:** Update student enrollment status
- **Reports:** View and export student data
- **Announcements:** Post and manage announcements
- **Dashboard:** Real-time statistics and recent activities

### 🛡️ Security Features
- **Secure Login System:** Password hashing
- **Role-based Access:** Admin, Student roles
- **Session Management:** Secure user sessions
- **Input Validation:** Server-side and client-side validation

### 📱 Responsive Design
- Works on desktop, tablet, and mobile
- Bootstrap 5 framework
- Modern, clean UI design

## 🚀 Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Step 1: Database Setup
1. Create a MySQL database named `enrollment_system`
2. Update database credentials in `config/database.php`:
   ```php
   $host = 'localhost';
   $dbname = 'enrollment_system';
   $username = 'your_username';
   $password = 'your_password';
   ```

### Step 2: Run Database Setup
1. Access `setup_database.php` in your browser
2. This will create all necessary tables and sample data
3. Default users will be created:
   - **Admin:** username: `admin`, password: `admin123`
   - **Student:** username: `student`, password: `student123`

### Step 3: Access the System
1. Navigate to `login.php`
2. Use the default credentials to log in
3. Start using the system!

## 📁 File Structure

```
├── index.php                 # Main dashboard
├── login.php                 # Login page
├── registration.php          # Student registration form
├── students.php             # Admin student management
├── announcements.php        # Admin announcements
├── logout.php              # Logout functionality
├── setup_database.php      # Database setup script
├── config/
│   └── database.php        # Database configuration
├── includes/
│   └── functions.php       # Helper functions
├── assets/
│   ├── css/
│   │   └── style.css      # Custom styles
│   └── js/
│       └── script.js      # JavaScript functionality
└── README.md              # This file
```

## 🎨 UI Features

### Color-coded Statistics Cards
- **Blue:** Enrolled Students
- **Yellow:** Pending Applicants  
- **Light Blue:** Male Students
- **Green:** Female Students

### Interactive Elements
- ✅ All buttons are fully functional
- ✅ Modal dialogs for actions
- ✅ Real-time form validation
- ✅ Success/error notifications
- ✅ Export to CSV functionality
- ✅ Responsive tables

## 🔐 User Roles

### Admin
- Access to all features
- Manage students (view, edit, delete)
- Update enrollment status
- Post announcements
- View reports and statistics

### Student
- View dashboard with statistics
- Register for enrollment
- View class schedule
- Read announcements

## 📊 Database Schema

### Tables
1. **users** - User accounts and roles
2. **students** - Student registration data
3. **announcements** - System announcements
4. **schedules** - Class schedules
5. **student_schedules** - Student-schedule relationships

## 🎯 Key Functionality

### Registration Process
1. Student fills out registration form
2. System validates all inputs
3. Student record created with 'pending' status
4. Admin can approve/reject enrollment
5. Dashboard statistics update automatically

### Admin Workflow
1. Login as admin
2. View pending applications
3. Update student status (pending → enrolled)
4. Manage announcements
5. Export reports

## 🔧 Customization

### Adding New Programs
Edit `registration.php` and add new options to the program dropdown:
```php
<option value="NEW_PROGRAM">New Program Name</option>
```

### Modifying Grade Levels
Update the grade level options in `registration.php`:
```php
<option value="Grade 13">Grade 13</option>
```

### Styling Changes
Modify `assets/css/style.css` for custom styling.

## 🚨 Security Notes

- All passwords are hashed using PHP's `password_hash()`
- SQL injection protection with prepared statements
- Input validation on both client and server side
- Session-based authentication
- Role-based access control

## 📞 Support

For issues or questions:
1. Check the database connection in `config/database.php`
2. Ensure all files have proper permissions
3. Verify PHP version compatibility
4. Check web server configuration

## 🎉 Getting Started

1. **Setup Database:** Run `setup_database.php`
2. **Login:** Use admin/admin123 or student/student123
3. **Test Registration:** Try the student registration form
4. **Manage Students:** Use admin panel to approve applications
5. **Post Announcements:** Create announcements for students

The system is now ready for use! 🎓