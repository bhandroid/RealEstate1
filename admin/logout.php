<?php
session_start();

// Only handle admin logout
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    unset($_SESSION['admin_email']);
    unset($_SESSION['role']);
    session_destroy();
    header("Location: ../login.php"); // Go back to shared login page
    exit();
}

// Fallback if no session or wrong role
session_destroy();
header("Location: ../index.php");
exit();
