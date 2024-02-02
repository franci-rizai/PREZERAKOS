<?php
require 'vendor/autoload.php';
use RedBeanPHP\R;

// Set up database connection
try {
    R::setup('mysql:host=sql11.freemysqlhosting.net;dbname=sql11681307', 'sql11681307', 'Y8LhenD1Xp');
} catch (Exception $e) {
    die('Error connecting to the database. ' . $e->getMessage());
}

session_start();

function getCurrentUser() {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        return R::findOne('users', 'username = ?', [$username]);
    }
    return null;
}

function makeAppointment($date, $time, $service, $user) {
    if ($user) {
        $appointment = R::dispense('appointments');
        $appointment->name = $user->name . ' ' . $user->surname;
        $appointment->date = $date . ' ' . $time;
        $appointment->service = $service;
        $id = R::store($appointment);
        return true;
    }
    return false;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data (perform input validation and sanitization)
    $date = htmlspecialchars($_POST['date']);
    $time = htmlspecialchars($_POST['time']);
    $service = htmlspecialchars($_POST['service']);

    // Get the logged-in user
    $currentUser = getCurrentUser();

    // Make the appointment
    if ($currentUser && makeAppointment($date, $time, $service, $currentUser)) {
        // Redirect to a success page
        header('Location: index.html');
        exit();
    } else {
        // Handle the case where the appointment couldn't be made
        echo 'Error making appointment';
    }
}
?>
