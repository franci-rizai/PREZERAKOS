<?php
require 'vendor/autoload.php';
use RedBeanPHP\R;

// Set up database connection
R::setup('mysql:host=sql11.freemysqlhosting.net;dbname=sql11681307', 'sql11681307', 'Y8LhenD1Xp');

session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Find the user by username
    $user = R::findOne('users', 'username = ?', [$_SESSION['username']]);

    if ($user) {
        // Return user data as JSON
        echo json_encode([
            'success' => true,
            'userFullName' => $user->name . ' ' . $user->surname
        ]);
        exit();
    }
}

// If not logged in or user not found
echo json_encode(['success' => false, 'message' => 'User not found']);
exit();
?>
