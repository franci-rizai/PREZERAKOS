<?php
require 'vendor/autoload.php';
use RedBeanPHP\R;

// Set up database connection
R::setup('mysql:host=127.0.0.1;dbname=barbershop', 'Suraj', '1234');

session_start();

// Function to delete an appointment by ID
function deleteAppointment($id) {
    $appointment = R::load('appointments', $id);
    if ($appointment->id) {
        R::trash($appointment);
        return true;
    }
    return false;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = $_POST['id'];

    // Attempt to delete the appointment
    if (deleteAppointment($id)) {
        // Return a success response
        echo json_encode(['success' => true, 'message' => 'Appointment deleted successfully']);
        exit();
    } else {
        // Return an error response with more details
        echo json_encode(['success' => false, 'message' => 'Error deleting appointment', 'id' => $id]);
        exit();
    }
}
?>
