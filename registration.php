<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $grade_level = $_POST['grade_level'];
    $program = $_POST['program'];
    
    // Validation
    $errors = [];
    
    if (empty($first_name)) $errors[] = 'First name is required';
    if (empty($last_name)) $errors[] = 'Last name is required';
    if (empty($date_of_birth)) $errors[] = 'Date of birth is required';
    if (empty($gender)) $errors[] = 'Gender is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (!validateEmail($email)) $errors[] = 'Please enter a valid email address';
    if (empty($phone)) $errors[] = 'Phone number is required';
    if (empty($grade_level)) $errors[] = 'Grade level is required';
    if (empty($program)) $errors[] = 'Program is required';
    
    // Check if email already exists
    if (studentExists($email)) {
        $errors[] = 'A student with this email already exists';
    }
    
    if (empty($errors)) {
        $student_data = [
            'student_id' => generateStudentId(),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'date_of_birth' => $date_of_birth,
            'gender' => $gender,
            'email' => $email,
            'phone' => $phone,
            'grade_level' => $grade_level,
            'program' => $program
        ];
        
        if (createStudent($student_data)) {
            $success = 'Registration successful! Your application is pending approval.';
        } else {
            $error = 'Registration failed. Please try again.';
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Online Enrollment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap"></i> Online Enrollment System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="registration.php">Register</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-plus"></i> Student Registration Form
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" id="registrationForm">
                            <!-- Personal Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user"></i> Personal Information
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                           value="<?php echo isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : ''; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender *</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Contact Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-address-book"></i> Contact Information
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
                                </div>
                            </div>
                            
                            <!-- Enrollment Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-graduation-cap"></i> Enrollment Information
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <label for="grade_level" class="form-label">Grade Level *</label>
                                    <select class="form-select" id="grade_level" name="grade_level" required>
                                        <option value="">Select Grade Level</option>
                                        <option value="Grade 7" <?php echo (isset($_POST['grade_level']) && $_POST['grade_level'] == 'Grade 7') ? 'selected' : ''; ?>>Grade 7</option>
                                        <option value="Grade 8" <?php echo (isset($_POST['grade_level']) && $_POST['grade_level'] == 'Grade 8') ? 'selected' : ''; ?>>Grade 8</option>
                                        <option value="Grade 9" <?php echo (isset($_POST['grade_level']) && $_POST['grade_level'] == 'Grade 9') ? 'selected' : ''; ?>>Grade 9</option>
                                        <option value="Grade 10" <?php echo (isset($_POST['grade_level']) && $_POST['grade_level'] == 'Grade 10') ? 'selected' : ''; ?>>Grade 10</option>
                                        <option value="Grade 11" <?php echo (isset($_POST['grade_level']) && $_POST['grade_level'] == 'Grade 11') ? 'selected' : ''; ?>>Grade 11</option>
                                        <option value="Grade 12" <?php echo (isset($_POST['grade_level']) && $_POST['grade_level'] == 'Grade 12') ? 'selected' : ''; ?>>Grade 12</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="program" class="form-label">Program *</label>
                                    <select class="form-select" id="program" name="program" required>
                                        <option value="">Select Program</option>
                                        <option value="STEM" <?php echo (isset($_POST['program']) && $_POST['program'] == 'STEM') ? 'selected' : ''; ?>>STEM (Science, Technology, Engineering, Mathematics)</option>
                                        <option value="ABM" <?php echo (isset($_POST['program']) && $_POST['program'] == 'ABM') ? 'selected' : ''; ?>>ABM (Accountancy, Business, Management)</option>
                                        <option value="HUMSS" <?php echo (isset($_POST['program']) && $_POST['program'] == 'HUMSS') ? 'selected' : ''; ?>>HUMSS (Humanities and Social Sciences)</option>
                                        <option value="GAS" <?php echo (isset($_POST['program']) && $_POST['program'] == 'GAS') ? 'selected' : ''; ?>>GAS (General Academic Strand)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus"></i> Register
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>