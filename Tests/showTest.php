use PHPUnit\Framework\TestCase;
use RedBeanPHP\R;

// Include the show_appointment.php script
require_once 'PREZERAKOS/show_appointment.php';

class ShowAppointmentTest extends TestCase
{
    protected function setUp(): void
    {
        // Set up a mock database connection for testing
        R::setup('sqlite::memory:');
        R::freeze(true); // Freeze RedBeanPHP to prevent schema changes
    }

    public function testLoggedInUserWithAppointments()
    {
        // Set up session data for a logged-in user
        $_SESSION['username'] = 'testuser';
        $_SESSION['userFullName'] = 'Test User';

        // Seed test data: Create appointments for the logged-in user
        $currentUser = getCurrentUser();
        if ($currentUser) {
            $appointment1 = R::dispense('appointments');
            $appointment1->name = $currentUser->name . ' ' . $currentUser->surname;
            $appointment1->date = '2024-04-15 09:00:00';
            $appointment1->service = 'Service 1';
            R::store($appointment1);

            // Add more appointments if needed
        }

        // Call the showAppointments function
        ob_start(); // Start output buffering to capture output
        showAppointments();
        $output = ob_get_clean(); // Get the output and stop buffering

        // Check if the JSON response contains the user's appointments
        $expectedJson = json_encode([$appointment1]); // Assuming only one appointment for simplicity
        $this->assertJsonStringEqualsJsonString($expectedJson, $output);
    }

    public function testUserNotLoggedInOrNotFound()
    {
        // Ensure no session data is set (user not logged in)
        $_SESSION = [];

        // Call the showAppointments function
        ob_start();
        showAppointments();
        $output = ob_get_clean();

        // Check if the JSON response indicates user not found
        $expectedJson = json_encode(['success' => false, 'message' => 'User not found']);
        $this->assertJsonStringEqualsJsonString($expectedJson, $output);
    }
}
