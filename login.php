<?php
require 'vendor/autoload.php'; // Adjust the path based on your project structure
include('index.html');
use RedBeanPHP\R;

// Set up database connection
R::setup('mysql:host=sql11.freemysqlhosting.net;dbname=sql11681307', 'sql11681307', 'Y8LhenD1Xp');

session_start();

if (!R::testConnection()) {
    die('Could not connect to the database. Check your connection settings.');
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Find the user by username
    $user = R::findOne('users', 'username = ?', [$username]);

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user->password)) {
        // Store user data in the session
        $_SESSION['username'] = $username;
        $_SESSION['userFullName'] = $user->name . ' ' . $user->surname;

        // Redirect to index.html
        echo '<script>
            window.location.href="index.html";
        </script>';
        exit();
    } else {
        // Authentication failed, show an error message or redirect to the login page
        echo 'Invalid username or password';
    }
}
?>
<!-- Rest of your HTML code -->
