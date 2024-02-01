<?php
require 'vendor/autoload.php'; // Adjust the path based on your project structure

use RedBeanPHP\R;

// Set up database connection
R::setup('mysql:host=192.168.31.8;dbname=Barbershop', 'franci', '123');

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
        // Authentication successful, redirect to a secure page
        echo "<script src='code.js' ></script>";
        header('Location: index.html');
        exit();
    } else {
        // Authentication failed, show an error message or redirect to the login page
        echo 'Invalid username or password';
    }
}
?>