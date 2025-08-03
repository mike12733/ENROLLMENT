# 🚀 Installation Guide for Online Enrollment System

## 📋 Prerequisites

- **XAMPP** (Apache + MySQL + PHP)
- **phpMyAdmin** (included with XAMPP)
- **Web Browser** (Chrome, Firefox, Safari, Edge)

## 🛠️ Step-by-Step Installation

### Step 1: Install XAMPP

1. **Download XAMPP**
   - Go to [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
   - Download XAMPP for your operating system (Windows, macOS, or Linux)

2. **Install XAMPP**
   - Run the installer
   - Follow the installation wizard
   - Default installation path: `C:\xampp` (Windows) or `/Applications/XAMPP` (macOS)

### Step 2: Start XAMPP Services

1. **Open XAMPP Control Panel**
   - Launch XAMPP Control Panel
   - Start **Apache** and **MySQL** services
   - Both services should show green status

2. **Verify Services**
   - Open browser and go to `http://localhost`
   - You should see the XAMPP welcome page

### Step 3: Create Database

1. **Access phpMyAdmin**
   - Open browser and go to `http://localhost/phpmyadmin`
   - Login with default credentials (usually no password)

2. **Create Database**
   - Click **"New"** on the left sidebar
   - Enter database name: `enrollment_system`
   - Click **"Create"**

### Step 4: Import Database

**Method 1: Using SQL File (Recommended)**

1. **Download the SQL file**
   - Use the `database.sql` file included in this project

2. **Import via phpMyAdmin**
   - Select the `enrollment_system` database
   - Click **"Import"** tab
   - Click **"Choose File"** and select `database.sql`
   - Click **"Go"** to import

**Method 2: Using Setup Script**

1. **Place files in XAMPP**
   - Copy all project files to `C:\xampp\htdocs\enrollment-system\`

2. **Run setup script**
   - Open browser and go to `http://localhost/enrollment-system/setup_database.php`
   - Follow the on-screen instructions

### Step 5: Configure Database Connection

1. **Edit database configuration**
   - Open `config/database.php`
   - Update credentials if needed:

```php
$host = 'localhost';
$dbname = 'enrollment_system';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password (empty)
```

### Step 6: Access the System

1. **Open the application**
   - Go to `http://localhost/enrollment-system/`
   - You'll be redirected to the login page

2. **Login with default credentials**
   - **Admin Account:**
     - Username: `admin`
     - Password: `admin123`
   - **Student Account:**
     - Username: `student`
     - Password: `student123`

## 🔧 Troubleshooting

### Common Issues and Solutions

#### Issue 1: "Connection failed" error
**Solution:**
- Check if MySQL service is running in XAMPP Control Panel
- Verify database credentials in `config/database.php`
- Ensure database `enrollment_system` exists

#### Issue 2: "Table doesn't exist" error
**Solution:**
- Import the database properly using the SQL file
- Check if all tables were created successfully
- Run the setup script again if needed

#### Issue 3: "Permission denied" error
**Solution:**
- Ensure XAMPP has proper permissions
- Check file permissions in the project directory
- Restart XAMPP services

#### Issue 4: Page not found (404 error)
**Solution:**
- Verify files are in the correct directory: `htdocs/enrollment-system/`
- Check Apache service is running
- Clear browser cache

#### Issue 5: Login not working
**Solution:**
- Verify database was imported correctly
- Check if users table has the default accounts
- Try running the setup script again

## 📁 File Structure

After installation, your directory should look like this:

```
C:\xampp\htdocs\enrollment-system\
├── index.php
├── login.php
├── registration.php
├── students.php
├── announcements.php
├── reports.php
├── profile.php
├── change_password.php
├── logout.php
├── setup_database.php
├── database.sql
├── config/
│   └── database.php
├── includes/
│   └── functions.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
├── README.md
└── INSTALLATION_GUIDE.md
```

## 🔐 Security Notes

### Default Passwords
- **Admin:** admin/admin123
- **Student:** student/student123

**⚠️ Important:** Change these passwords after first login!

### Database Security
- Change default MySQL root password
- Create a dedicated database user
- Restrict database access permissions

## 🎯 Quick Start Checklist

- [ ] XAMPP installed and running
- [ ] Apache and MySQL services started
- [ ] Database `enrollment_system` created
- [ ] SQL file imported successfully
- [ ] Files copied to `htdocs/enrollment-system/`
- [ ] Database configuration updated
- [ ] System accessible at `http://localhost/enrollment-system/`
- [ ] Can login with default credentials
- [ ] All features working properly

## 📞 Support

If you encounter issues:

1. **Check XAMPP logs**
   - Apache logs: `C:\xampp\apache\logs\`
   - MySQL logs: `C:\xampp\mysql\data\`

2. **Verify database connection**
   - Test connection in phpMyAdmin
   - Check database credentials

3. **Common fixes**
   - Restart XAMPP services
   - Clear browser cache
   - Re-import database

## 🎉 Success!

Once you can access the system and login successfully, you're ready to use the Online Enrollment System!

**Next Steps:**
1. Change default passwords
2. Test all features
3. Add real student data
4. Customize as needed

---

**System Features:**
- ✅ Student Registration
- ✅ Admin Dashboard
- ✅ Student Management
- ✅ Announcements
- ✅ Reports and Statistics
- ✅ Export to CSV
- ✅ Responsive Design
- ✅ Secure Authentication

Happy coding! 🎓