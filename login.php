<?php
require 'vendor/autoload.php';

use RedBeanPHP\R;

// Function to handle user authentication
function authenticateUser($db, $username, $password) {
    // Find the user by username using the provided database connection
    $user = $db::findOne('users', 'username = ?', [$username]);

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user->password)) {
        return $user;
    } else {
        return false;
    }
}

// Set up database connection
R::setup('mysql:host=127.0.0.1;dbname=barbershop', 'Suraj', '1234');

session_start();

if (!R::testConnection()) {
    die('Could not connect to the database. Check your connection settings.');
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Authenticate the user using the authenticateUser function with dependency injection
    $authenticatedUser = authenticateUser(R::class, $username, $password);

    if ($authenticatedUser) {
        // Store user data in the session
        $_SESSION['username'] = $username;
        $_SESSION['userFullName'] = $authenticatedUser->name . ' ' . $authenticatedUser->surname;

        // Redirect to index.html
        echo '<script> 
        var name = "' . $authenticatedUser->name . ' ' . $authenticatedUser->surname .'"; 
        localStorage.setItem("name", name);
            window.location.href="index.html";
        </script>';
        exit();
    } else {
        echo '<script> 
        window.location.href="Sign_in.html";
        alert("Wrong Username/Password");
        </script>';
    }
} elseif(isset($_SESSION['username'])) {
    echo '<script> 
    window.location.href="index.html";
    </script>';
    exit();
}
?>
