<?php
require 'vendor/autoload.php';
use RedBeanPHP\R;

R::setup('localhost', 'root', '');

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

        // Return user appointments as JSON
        header('Content-Type: application/json');
        echo json_encode($userAppointments);
        exit();
    }
}

// If not logged in or user not found
echo json_encode(['success' => false, 'message' => 'User not found']);
exit();
?>
