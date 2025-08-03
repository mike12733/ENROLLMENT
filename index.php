<?php
require_once 'includes/session.php';

// Redirect based on login status
if (isLoggedIn()) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: dashboard.php');
    }
} else {
    header('Location: login.php');
}
exit();
?>