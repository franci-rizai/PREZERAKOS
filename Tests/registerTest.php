use PHPUnit\Framework\TestCase;
use RedBeanPHP\R;

// Include the register.php script
require_once 'PREZERAKOS/register.php';

class RegisterTest extends TestCase
{
    protected function setUp(): void
    {
        // Set up a mock database connection for testing
        R::setup('sqlite::memory:');
        R::freeze(true); // Freeze RedBeanPHP to prevent schema changes
    }

    public function testRegistrationSuccess()
    {
        // Simulate form submission with valid data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = 'Test';
        $_POST['surname'] = 'User';
        $_POST['email'] = 'test@example.com';
        $_POST['username'] = 'testuser';
        $_POST['password'] = 'password';

        // Call the register function
        ob_start(); // Start output buffering to capture output
        register();
        $output = ob_get_clean(); // Get the output and stop buffering

        // Check if the user is redirected to Sign_in.html upon successful registration
        $this->assertStringContainsString('Location: Sign_in.html', $output);
    }

}
