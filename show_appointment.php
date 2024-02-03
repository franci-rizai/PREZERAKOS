<?php
require 'vendor/autoload.php';
use RedBeanPHP\R;

// Set up database connection
R::setup('mysql:host=sql11.freemysqlhosting.net;dbname=sql11681307', 'sql11681307', 'Y8LhenD1Xp');

session_start();

function getCurrentUser() {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        return R::findOne('users', 'username = ?', [$username]);
    }
    return null;
}

function getUserAppointments($userFullName) {
    // Get the user's appointments based on the user's full name
    return R::findAll('appointments', 'name = ?', [$userFullName]);
}

// Check if the user is logged in
if (isset($_SESSION['username'], $_SESSION['userFullName'])) {
    // Get the logged-in user
    $currentUser = getCurrentUser();

    if ($currentUser) {
        // Get the user's appointments
        $userAppointments = getUserAppointments($_SESSION['userFullName']);
    }
    echo '<script> console.log(' . json_encode($userAppointments) . ');</script>';
}

// Use $userAppointments as needed
?>
