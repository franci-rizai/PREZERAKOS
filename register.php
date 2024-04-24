<?php
require 'vendor/autoload.php'; // Adjust the path based on your project structure

use RedBeanPHP\R;

// Set up database connection
R::setup('mysql:host=127.0.0.1;dbname=barbershop', 'Suraj', '1234');
if (!R::testConnection()) {
    die('Could not connect to the database. Check your connection settings.');
}

// Get the raw POST data
$inputJSON = file_get_contents('php://input');
// Decode the JSON data
$input = json_decode($inputJSON);

// Check if the form data is valid
if (!empty($input->firstName) && !empty($input->lastName) && !empty($input->email) && !empty($input->username) && !empty($input->password)) {
    // Retrieve user input
    $name = $input->firstName;
    $surname = $input->lastName;
    $email = $input->email;
    $username = $input->username;
    $password = $input->password;

    // Create a RedBean bean for the 'users' table
    $users = R::dispense('users');

    // Set properties for the user
    $users->name = $name;
    $users->surname = $surname;
    $users->email = $email;
    $users->username = $username;
    $users->password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Store the user in the database
    $id = R::store($users);

    if ($id) {
        // Registration successful
        $response = ['success' => true, 'message' => 'Registration successful'];
    } else {
        // Registration failed
        $response = ['success' => false, 'message' => 'Registration failed'];
    }
} else {
    // Invalid form data
    $response = ['success' => false, 'message' => 'Invalid form data'];
}

// Set the correct content type for JSON
header('Content-Type: application/json');
// Output the JSON response
echo json_encode($response);
// Exit to prevent any further output
exit();
?>
