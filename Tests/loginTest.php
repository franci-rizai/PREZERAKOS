use PHPUnit\Framework\TestCase;
use RedBeanPHP\R;

// Include the login.php script
require_once 'PREZERAKOS/login.php';

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        // Set up a mock database connection for testing
        R::setup('sqlite::memory:'); // Use an in-memory SQLite database for testing
        R::freeze(true); // Freeze RedBeanPHP to prevent schema changes
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        R::nuke(); // Clear all tables in the test database
    }

    public function testValidLogin()
    {
        // Seed the test database with a user
        $user = R::dispense('users');
        $user->username = 'testuser';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        R::store($user);

        // Set up POST data for a valid login attempt
        $_POST['username'] = 'testuser';
        $_POST['password'] = 'password';

        // Call the login function
        ob_start(); // Start output buffering to capture output
        login();
        $output = ob_get_clean(); // Get the output and stop buffering

        // Check if the user is redirected to index.html upon successful login
        $this->assertStringContainsString('Location: index.html', $output);
    }

    public function testInvalidLogin()
    {
        // Set up POST data for an invalid login attempt
        $_POST['username'] = 'invaliduser';
        $_POST['password'] = 'invalidpassword';

        // Call the login function
        ob_start();
        login();
        $output = ob_get_clean();

        // Check if the user is redirected to Sign_in.html upon unsuccessful login
        $this->assertStringContainsString('Location: Sign_in.html', $output);
        // Check if an error message is displayed
        $this->assertStringContainsString('alert("Wrong Username/Password")', $output);
    }
}
