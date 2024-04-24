<?php
require 'vendor/autoload.php';
use RedBeanPHP\R;

// Function to make appointment with dependency injection
function makeAppointment($db, $date, $time, $service, $user) {
    if ($user) {
        // Check if the appointment already exists using the provided database connection
        $existingAppointment = $db::findOne('appointments', 'name = ? AND date = ? AND service = ?', [
            $user->name . ' ' . $user->surname,
            $date . ' ' . $time,
            $service
        ]);

        if ($existingAppointment) {
            // Handle the case where the appointment already exists (e.g., show an error message)
            return false;
        }

        // If the appointment doesn't exist, create a new one
        $appointment = $db::dispense('appointments');
        $appointment->name = $user->name . ' ' . $user->surname;
        $appointment->date = $date . ' ' . $time;
        $appointment->service = $service;
        $id = $db::store($appointment);
        return true;
    }
    return false;
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
    // Retrieve form data (perform input validation and sanitization)
    $date = htmlspecialchars($_POST['date']);
    $time = htmlspecialchars($_POST['time']);
    $service = htmlspecialchars($_POST['service']);

    // Get the logged-in user using dependency injection
    $currentUser = getCurrentUser(R::class);

    // Make the appointment using dependency injection
    if ($currentUser && makeAppointment(R::class, $date, $time, $service, $currentUser)) {
        // Redirect to a success page
        header('Location: index.html');
        exit();
    } else {
        // Handle the case where the appointment couldn't be made
        echo ' <script> alert("Sorry, Current Date and Time is Taken");
        window.location.href="Make_appointment.html";
        </script>';
    }
}
?>
