use PHPUnit\Framework\TestCase;
use RedBeanPHP\R;

// Include the delete_appointment.php script
require_once 'PREZERAKOS/delete_appointment.php';

class DeleteAppointmentTest extends TestCase
{
    protected function setUp(): void
    {
        // Set up a mock database connection for testing
        R::setup('sqlite::memory:');
        R::freeze(true); // Freeze RedBeanPHP to prevent schema changes
    }

    public function testDeleteExistingAppointment()
    {
        // Seed test data: Create an appointment in the database
        $appointment = R::dispense('appointments');
        $appointment->date = '2024-04-15';
        // Set other properties if needed
        $id = R::store($appointment);

        // Simulate form submission with the appointment ID
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['id'] = $id;

        // Call the deleteAppointment function
        ob_start(); // Start output buffering to capture output
        deleteAppointment($id);
        $output = ob_get_clean(); // Get the output and stop buffering

        // Check if the JSON response indicates success
        $expectedJson = json_encode(['success' => true, 'message' => 'Appointment deleted successfully']);
        $this->assertJsonStringEqualsJsonString($expectedJson, $output);
    }

    public function testDeleteNonExistingAppointment()
    {
        // Simulate form submission with a non-existing appointment ID
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['id'] = 999; // Assuming ID 999 does not exist

        // Call the deleteAppointment function
        ob_start();
        deleteAppointment($_POST['id']);
        $output = ob_get_clean();

        // Check if the JSON response indicates failure with appropriate message and ID
        $expectedJson = json_encode(['success' => false, 'message' => 'Error deleting appointment', 'id' => 999]);
        $this->assertJsonStringEqualsJsonString($expectedJson, $output);
    }
}
