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

function getUserAppointments($userId) {
    return R::findAll('appointments', 'user_id = ?', [$userId]);
}

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Get the logged-in user
    $currentUser = getCurrentUser();

    if ($currentUser) {
        // Get the user's appointments
        $userAppointments = getUserAppointments($currentUser->id);
    }
}

?>