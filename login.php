<?php
require 'vendor/autoload.php';
use RedBeanPHP\R;

// Set up database connection
R::setup('mysql:host=sql11.freemysqlhosting.net;dbname=sql11681307', 'sql11681307', 'Y8LhenD1Xp');

session_start();

function authenticateUser($username, $password) {
    // Find the user by username
    $user = R::findOne('users', 'username = ?', [$username]);

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user->password)) {
        // Authentication successful
        $_SESSION['username'] = $username; // Set session variable
        $_SESSION['userFullName'] = $user->name . ' ' . $user->surname; // Set user's full name in session
        return true;
    }

    // Authentication failed
    return false;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Authenticate user
    if (authenticateUser($username, $password)) {
        // Redirect to a secure page
        header('Location: index.html');
        exit();
    } else {
        // Authentication failed, show an error message
        echo 'Invalid username or password';
    }
}
?>
