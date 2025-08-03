<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$success = '';
$error = '';
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = $_POST;
    
    // Validate form data
    $errors = validateRegistrationData($_POST);
    
    if (empty($errors)) {
        // Register student
        $db = new Database();
        $pdo = $db->getConnection();
        
        $result = registerStudent($pdo, $_POST);
        
        if ($result['success']) {
            $success = $result['message'];
            $formData = []; // Clear form on success
        } else {
            $error = $result['message'];
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
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">Online Enrollment System</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h1 class="form-title">Student Registration Form</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <div class="text-center">
                    <a href="login.php" class="btn btn-primary">Login Now</a>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!$success): ?>
                <form id="registrationForm" method="POST" action="">
                    <div class="form-grid">
                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <h3 class="section-title">📋 Personal Information</h3>
                            
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" required 
                                       value="<?php echo htmlspecialchars($formData['first_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" required 
                                       value="<?php echo htmlspecialchars($formData['last_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth *</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" required 
                                       value="<?php echo htmlspecialchars($formData['date_of_birth'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="gender">Gender *</label>
                                <select id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo (($formData['gender'] ?? '') === 'male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo (($formData['gender'] ?? '') === 'female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="form-section">
                            <h3 class="section-title">📞 Contact Information</h3>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required 
                                       value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>"
                                       placeholder="e.g., +63 912 345 6789">
                            </div>
                        </div>

                        <!-- Enrollment Information Section -->
                        <div class="form-section">
                            <h3 class="section-title">🎓 Enrollment Information</h3>
                            
                            <div class="form-group">
                                <label for="student_id">Student ID *</label>
                                <div style="display: flex; gap: 0.5rem;">
                                    <input type="text" id="student_id" name="student_id" required 
                                           value="<?php echo htmlspecialchars($formData['student_id'] ?? ''); ?>"
                                           placeholder="e.g., 20240001">
                                    <button type="button" id="generateStudentId" class="btn btn-secondary" 
                                            style="white-space: nowrap;">Generate</button>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="grade_level">Grade Level *</label>
                                <select id="grade_level" name="grade_level" required>
                                    <option value="">Select Grade Level</option>
                                    <option value="Grade 7" <?php echo (($formData['grade_level'] ?? '') === 'Grade 7') ? 'selected' : ''; ?>>Grade 7</option>
                                    <option value="Grade 8" <?php echo (($formData['grade_level'] ?? '') === 'Grade 8') ? 'selected' : ''; ?>>Grade 8</option>
                                    <option value="Grade 9" <?php echo (($formData['grade_level'] ?? '') === 'Grade 9') ? 'selected' : ''; ?>>Grade 9</option>
                                    <option value="Grade 10" <?php echo (($formData['grade_level'] ?? '') === 'Grade 10') ? 'selected' : ''; ?>>Grade 10</option>
                                    <option value="Grade 11" <?php echo (($formData['grade_level'] ?? '') === 'Grade 11') ? 'selected' : ''; ?>>Grade 11</option>
                                    <option value="Grade 12" <?php echo (($formData['grade_level'] ?? '') === 'Grade 12') ? 'selected' : ''; ?>>Grade 12</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="program">Program/Track *</label>
                                <select id="program" name="program" required>
                                    <option value="">Select Program</option>
                                    <option value="STEM" <?php echo (($formData['program'] ?? '') === 'STEM') ? 'selected' : ''; ?>>Science, Technology, Engineering, and Mathematics (STEM)</option>
                                    <option value="ABM" <?php echo (($formData['program'] ?? '') === 'ABM') ? 'selected' : ''; ?>>Accountancy, Business and Management (ABM)</option>
                                    <option value="HUMSS" <?php echo (($formData['program'] ?? '') === 'HUMSS') ? 'selected' : ''; ?>>Humanities and Social Sciences (HUMSS)</option>
                                    <option value="GAS" <?php echo (($formData['program'] ?? '') === 'GAS') ? 'selected' : ''; ?>>General Academic Strand (GAS)</option>
                                    <option value="TVL-ICT" <?php echo (($formData['program'] ?? '') === 'TVL-ICT') ? 'selected' : ''; ?>>Technical-Vocational-Livelihood (TVL) - ICT</option>
                                    <option value="TVL-HE" <?php echo (($formData['program'] ?? '') === 'TVL-HE') ? 'selected' : ''; ?>>Technical-Vocational-Livelihood (TVL) - Home Economics</option>
                                    <option value="Arts and Design" <?php echo (($formData['program'] ?? '') === 'Arts and Design') ? 'selected' : ''; ?>>Arts and Design</option>
                                    <option value="Sports" <?php echo (($formData['program'] ?? '') === 'Sports') ? 'selected' : ''; ?>>Sports Track</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-full">
                            Register Student
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="login.php" style="color: #667eea;">Login here</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>