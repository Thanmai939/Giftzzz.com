<?php
session_start();

// Set 'logged_in' session variable to false during logout
$_SESSION['logged_in'] = true;

// Logout functionality
session_unset();
session_destroy();

// Redirect to landing page
header("Location: index.html");
exit;
?>