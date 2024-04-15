<?php
require 'vendor/autoload.php';
use RedBeanPHP\R;

// Set up database connection
R::setup('localhost', 'root', '');

session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Fetch user data from the session
    $userFullName = isset($_SESSION['userFullName']) ? $_SESSION['userFullName'] : '';

    // Return user data as JSON
    echo json_encode([
        'success' => true,
        'userFullName' => $userFullName
    ]);
    exit();
}

// If not logged in
echo json_encode(['success' => false, 'message' => 'User not found']);
exit();
?>
