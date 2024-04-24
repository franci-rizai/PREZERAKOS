<?php
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase {
    // Set up the test environment
    public function setUp(): void {
        // Set up your test environment here, e.g., database connection, test data setup
        // Make sure to use a separate testing database to avoid data corruption in the production database
        // You may also need to set up autoloaders or include necessary files
        require_once 'vendor/autoload.php'; // Adjust the path based on your project structure
        require_once 'register.php';
        // Include any other necessary files
        
        // Set up a testing database connection
        R::setup('mysql:host=127.0.0.1;dbname=test_barbershop', 'test_user', 'test_password');
    }

    // Test User Registration
    public function testUserRegistration() {
        // Simulate user input
        $_POST['firstName'] = 'John';
        $_POST['lastName'] = 'Doe';
        $_POST['email'] = 'john@example.com';
        $_POST['username'] = 'john_doe';
        $_POST['password'] = 'password';

        // Capture the output of the registration script
        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        // Decode the JSON response
        $response = json_decode($output, true);

        // Assert that registration was successful
        $this->assertEquals(true, $response['success']);

        // Optionally, you can also assert that the user data is correctly stored in the database
        // You may need to query the database to check if the user exists and has the correct data
        // Example:
        // $user = R::findOne('users', 'username = ?', ['john_doe']);
        // $this->assertNotNull($user);
        // $this->assertEquals('John', $user->name);
        // $this->assertEquals('Doe', $user->surname);
        // Add more assertions as needed
    }

    // Clean up the test environment
    public function tearDown(): void {
        // Clean up any test data or resources created during testing
        // Reset the database to its initial state if necessary
        // Close any open connections or files
    }
}
?>
