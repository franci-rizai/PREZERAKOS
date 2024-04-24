<?php
require 'vendor/autoload.php';
use RedBeanPHP\R;

// Function to make appointment with dependency injection
function makeAppointment($db, $date, $time, $service, $user) {
    // Check if the user is logged in
    if ($user) {
        // Sanitize input data
        $date = htmlspecialchars($date);
        $time = htmlspecialchars($time);
        $service = htmlspecialchars($service);

        // Check if the appointment already exists
        $existingAppointment = $db::findOne('appointments', 'name = ? AND date = ? AND service = ?', [
            $user->name . ' ' . $user->surname,
            $date . ' ' . $time,
            $service
        ]);

        if ($existingAppointment) {
            // Handle the case where the appointment already exists (e.g., show an error message)
            return json_encode(['success' => false, 'message' => 'Appointment already exists']);
        }

        // If the appointment doesn't exist, create a new one
        $appointment = $db::dispense('appointments');
        $appointment->name = $user->name . ' ' . $user->surname;
        $appointment->date = $date . ' ' . $time;
        $appointment->service = $service;
        $id = $db::store($appointment);
        return json_encode(['success' => true, 'message' => 'Appointment created successfully']);
    }
    return json_encode(['success' => false, 'message' => 'User not logged in']);
}

// Set up database connection
try {
    R::setup('mysql:host=127.0.0.1;dbname=barbershop', 'Suraj', '1234');
} catch (Exception $e) {
    die('Error connecting to the database. ' . $e->getMessage());
}

session_start();

// Function to retrieve the current user with dependency injection
function getCurrentUser($db) {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        return $db::findOne('users', 'username = ?', [$username]);
    }
    return null;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = $_POST['service'];

    // Get the logged-in user using dependency injection
    $currentUser = getCurrentUser(R::class);

    // Make the appointment using dependency injection
    echo makeAppointment(R::class, $date, $time, $service, $currentUser);
}
?>
