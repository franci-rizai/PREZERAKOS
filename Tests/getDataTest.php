use PHPUnit\Framework\TestCase;
use RedBeanPHP\R;

// Include the get_user_data.php script
require_once 'path/to/your/get_user_data.php';

class GetUserDataTest extends TestCase
{
    protected function setUp(): void
    {
        // Set up a mock database connection for testing
        R::setup('sqlite::memory:');
        R::freeze(true); // Freeze RedBeanPHP to prevent schema changes
    }

    public function testLoggedInUser()
    {
        // Set up session data for a logged-in user
        $_SESSION['username'] = 'testuser';

        // Seed test data: Create a user in the database
        $user = R::dispense('users');
        $user->username = 'testuser';
        // Set other properties if needed
        R::store($user);

        // Call the getUserData function
        ob_start(); // Start output buffering to capture output
        getUserData();
        $output = ob_get_clean(); // Get the output and stop buffering

        // Check if the JSON response contains the user data
        $expectedJson = json_encode([
            'success' => true,
            'userFullName' => $user->name . ' ' . $user->surname
        ]);
        $this->assertJsonStringEqualsJsonString($expectedJson, $output);
    }

    public function testUserNotLoggedIn()
    {
        // Ensure no session data is set (user not logged in)
        $_SESSION = [];

        // Call the getUserData function
        ob_start();
        getUserData();
        $output = ob_get_clean();

        // Check if the JSON response indicates user not found
        $expectedJson = json_encode(['success' => false, 'message' => 'User not found']);
        $this->assertJsonStringEqualsJsonString($expectedJson, $output);
    }
}
