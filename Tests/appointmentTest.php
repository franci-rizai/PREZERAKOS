use PHPUnit\Framework\TestCase;
use RedBeanPHP\R;

// Include the make_appointment.php script
require_once 'path/to/your/make_appointment.php';

class MakeAppointmentTest extends TestCase
{
    protected function setUp(): void
    {
        // Set up a mock database connection for testing
        R::setup('sqlite::memory:');
        R::freeze(true); // Freeze RedBeanPHP to prevent schema changes
    }

    public function testValidAppointment()
    {
        // Set up session data for a logged-in user
        $_SESSION['username'] = 'testuser';

        // Simulate form submission with valid data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['date'] = '2024-04-15';
        $_POST['time'] = '09:00';
        $_POST['service'] = 'Service';

        // Call the makeAppointment function
        ob_start(); // Start output buffering to capture output
        makeAppointment($_POST['date'], $_POST['time'], $_POST['service'], getCurrentUser());
        $output = ob_get_clean(); // Get the output and stop buffering

        // Check if the user is redirected to index.html upon successful appointment creation
        $this->assertStringContainsString('Location: index.html', $output);
    }

    public function testInvalidAppointment()
    {
        // Set up session data for a logged-in user
        $_SESSION['username'] = 'testuser';

        // Simulate form submission with invalid data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        // Assuming the appointment already exists for the given date, time, and service
        $_POST['date'] = '2024-04-15';
        $_POST['time'] = '09:00';
        $_POST['service'] = 'Service';

        // Call the makeAppointment function
        ob_start();
        makeAppointment($_POST['date'], $_POST['time'], $_POST['service'], getCurrentUser());
        $output = ob_get_clean();

        // Check if the user is redirected to Make_appointment.html upon unsuccessful appointment creation
        $this->assertStringContainsString('Location: Make_appointment.html', $output);
        // Check if an error message is displayed
        $this->assertStringContainsString('Sorry, Current Date and Time is Taken', $output);
    }
}
