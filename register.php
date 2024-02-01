<?php
require 'vendor/autoload.php'; // Adjust the path based on your project structure

use RedBeanPHP\R;

// Set up database connection
R::setup('mysql:host=sql11.freemysqlhosting.net;dbname=sql11681307', 'sql11681307', 'Y8LhenD1Xp');

if (!R::testConnection()) {
    die('Could not connect to the database. Check your connection settings.');
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create a RedBean bean for the 'users' table
    $users = R::dispense('users');

    // Set properties for the user
    $users->name = $name;
    $users->surname = $surname;
    $users->email = $email;
    $users->username = $username;
    $users->password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Since 'id' is auto-incremented, we don't need to set it explicitly

    // Store the user in the database
    R::store($users);

    // Redirect to a success page or perform other actions
    header('Location: index.html'); // Change this to your success page
    exit();
}
?>