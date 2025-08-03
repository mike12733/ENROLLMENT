<?php
require_once 'includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access - Online Enrollment System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">⚠️ Unauthorized Access</h1>
        
        <div class="alert alert-error">
            You don't have permission to access this page.
        </div>
        
        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </div>
</body>
</html>