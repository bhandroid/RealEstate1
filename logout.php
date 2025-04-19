<?php
session_start();

// Handle admin logout
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    unset($_SESSION['admin_email']);
    unset($_SESSION['role']);
    session_destroy();
    header("Location: login.php"); // Redirect to shared login page (or admin/login.php if separate)
    exit();
}

// Handle user logout
if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    unset($_SESSION['email']);
    unset($_SESSION['uid']);
    unset($_SESSION['role']);
    session_destroy();
    header("Location: index.php"); // Redirect to homepage
    exit();
}

// Fallback (if no session)
session_destroy();
header("Location: index.php");
exit();
