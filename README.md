# 🎓 Online Enrollment System

A complete web-based enrollment system for high schools built with PHP, MySQL, HTML, CSS, and JavaScript. This system allows students to register online and provides administrators with comprehensive management tools.

## ✨ Features

### 🏠 Student Dashboard
- **4 Colored Statistical Tiles**: Display counts for enrolled students, pending applicants, male students, and female students
- **Class Schedule Table**: Shows subjects, times, days, and rooms
- **Recent Announcements**: Latest school announcements and updates
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices

### 📝 Student Registration Form
- **Personal Information Section**: First name, last name, date of birth, gender
- **Contact Information Section**: Email address and phone number
- **Enrollment Information Section**: Student ID (with auto-generator), grade level, program selection
- **Real-time Validation**: Client-side and server-side form validation
- **Success Confirmation**: Redirects to confirmation page after successful registration

### 👨‍💼 Admin Panel
- **Student Management**: Add, edit, delete, and approve/reject student applications
- **Status Updates**: Change student status (pending, enrolled, rejected)
- **Announcement System**: Post announcements for different user groups
- **Statistics Dashboard**: Real-time student counts and analytics
- **User Management**: Role-based access control

### 🔐 Authentication System
- **Secure Login**: Password hashing and session management
- **Role-based Access**: Admin, Registrar, Teacher, Student roles
- **Session Security**: Proper session handling and logout functionality

### 📱 Responsive Design
- **Mobile-First**: Optimized for all screen sizes
- **Modern UI**: Beautiful gradient backgrounds and card-based layout
- **Interactive Elements**: Hover effects, smooth animations, and transitions
- **Accessibility**: Proper form labels and keyboard navigation

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Styling**: Custom CSS with CSS Grid and Flexbox
- **Security**: PDO prepared statements, password hashing, CSRF protection

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## 🚀 Installation

1. **Clone or Download** the project files to your web server directory

2. **Database Setup**:
   ```bash
   # Import the database schema
   mysql -u root -p < database_setup.sql
   ```

3. **Configure Database Connection**:
   - Edit `config/database.php`
   - Update the database credentials:
   ```php
   private $host = 'localhost';
   private $dbname = 'online_enrollment_system';
   private $username = 'your_username';
   private $password = 'your_password';
   ```

4. **Set Permissions**:
   ```bash
   chmod 755 -R /path/to/project
   chmod 777 -R /path/to/project/uploads (if you add file upload features)
   ```

5. **Access the System**:
   - Open your web browser
   - Navigate to `http://localhost/your-project-folder`

## 👤 Default Login

- **Admin Username**: `admin`
- **Admin Password**: `password`

## 📁 Project Structure

```
online-enrollment-system/
├── admin/
│   └── dashboard.php          # Admin panel
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   └── js/
│       └── script.js         # JavaScript functionality
├── config/
│   └── database.php          # Database configuration
├── includes/
│   ├── functions.php         # Helper functions
│   └── session.php           # Session management
├── database_setup.sql        # Database schema and sample data
├── dashboard.php             # Main dashboard
├── login.php                 # Login page
├── register.php              # Student registration
├── logout.php                # Logout handler
├── index.php                 # Landing page
└── README.md                 # This file
```

## 🎯 Usage Guide

### For Students:
1. **Register**: Go to the registration page and fill in your information
2. **Login**: Use your credentials to access the dashboard
3. **View Schedule**: Check your class schedule and announcements
4. **Update Profile**: Modify your information as needed

### For Administrators:
1. **Login**: Use admin credentials to access the admin panel
2. **Manage Students**: Approve, reject, or delete student applications
3. **Post Announcements**: Share important information with students
4. **View Statistics**: Monitor enrollment numbers and demographics

## 🔒 Security Features

- **Password Hashing**: Secure bcrypt password hashing
- **SQL Injection Protection**: PDO prepared statements
- **XSS Prevention**: Proper output escaping
- **Session Security**: Secure session management
- **Input Validation**: Client-side and server-side validation
- **Role-based Access**: Proper authorization checks

## 🎨 Customization

### Colors and Styling:
- Edit `assets/css/style.css` to change colors, fonts, and layout
- Modify CSS variables for quick color scheme changes

### Database Schema:
- Add new fields to existing tables as needed
- Create new tables for additional features
- Update functions in `includes/functions.php`

### Features:
- Add new user roles in the users table
- Implement file upload for student documents
- Add email notifications for status changes
- Create printable reports and certificates

## 🐛 Troubleshooting

### Common Issues:

1. **Database Connection Error**:
   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify database exists and is accessible

2. **Permission Denied**:
   - Check file permissions (755 for directories, 644 for files)
   - Ensure web server can read/write to necessary directories

3. **Session Issues**:
   - Check PHP session configuration
   - Ensure `/tmp` directory is writable
   - Clear browser cookies

4. **Responsive Design Issues**:
   - Clear browser cache
   - Test on different devices/screen sizes
   - Check CSS media queries

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 📞 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the troubleshooting section above

## 🚀 Future Enhancements

- [ ] Email notifications
- [ ] PDF report generation  
- [ ] Advanced search and filtering
- [ ] Bulk student import/export
- [ ] Grade management system
- [ ] Parent portal access
- [ ] Mobile app development
- [ ] API development for third-party integrations

---

**Developed with ❤️ for educational institutions looking to modernize their enrollment process.**