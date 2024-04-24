<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class IntegrationTest extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        // Set up Guzzle HTTP client
        $this->client = new Client([
            'base_uri' => 'http://localhost/PREZERAKOS', // Adjust the base URI
            'http_errors' => false, // Don't throw exceptions for HTTP errors
        ]);
    }

    public function testRegister()
    {
        $response = $this->client->post('register.php', [
            'form_params' => [
                'name' => 'John',
                'surname' => 'Doe',
                'email' => 'john.doe@example.com',
                'username' => 'johndoe',
                'password' => 'password123',
            ],
        ]);

        $this->assertEquals(302, $response->getStatusCode(), 'Registration request sent successfully'); // Success message for assertion
        $this->assertStringContainsString('Sign_in.html', $response->getHeaderLine('Location'), 'Redirected to sign-in page after registration'); // Success message for assertion
    }

    public function testLogin()
    {
        $response = $this->client->post('login.php', [
            'form_params' => [
                'username' => 'johndoe',
                'password' => 'password123',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Login request sent successfully'); // Success message for assertion
        $this->assertStringContainsString('index.html', $response->getBody()->getContents(), 'Successfully logged in and redirected to index.html'); // Success message for assertion
    }

    public function testMakeAppointment()
    {
        // Simulate a logged-in user session
        $this->client->post('login.php', [
            'form_params' => [
                'username' => 'johndoe',
                'password' => 'password123',
            ],
        ]);

        // Make an appointment
        $response = $this->client->post('make_appointment.php', [
            'form_params' => [
                'date' => '2024-04-15',
                'time' => '09:00',
                'service' => 'Service',
            ],
        ]);

        $this->assertEquals(302, $response->getStatusCode(), 'Appointment request sent successfully'); // Success message for assertion
        $this->assertStringContainsString('index.html', $response->getHeaderLine('Location'), 'Redirected to index.html after making appointment'); // Success message for assertion
    }

    public function testShowAppointment()
    {
        // Simulate a logged-in user session
        $this->client->post('login.php', [
            'form_params' => [
                'username' => 'johndoe',
                'password' => 'password123',
            ],
        ]);

        $response = $this->client->get('show_appointment.php');

        $this->assertEquals(200, $response->getStatusCode(), 'Show appointment request sent successfully'); // Success message for assertion
        $this->assertStringContainsString('userFullName', $response->getBody()->getContents(), 'User appointments retrieved successfully'); // Success message for assertion
    }

    public function testGetUserData()
    {
        // Simulate a logged-in user session
        $this->client->post('login.php', [
            'form_params' => [
                'username' => 'johndoe',
                'password' => 'password123',
            ],
        ]);

        $response = $this->client->get('get_user_data.php');

        $this->assertEquals(200, $response->getStatusCode(), 'Get user data request sent successfully'); // Success message for assertion
        $this->assertStringContainsString('userFullName', $response->getBody()->getContents(), 'User data retrieved successfully'); // Success message for assertion
    }

    public function testDeleteAppointment()
    {
        // Simulate a logged-in user session
        $this->client->post('login.php', [
            'form_params' => [
                'username' => 'johndoe',
                'password' => 'password123',
            ],
        ]);

        // Create a new appointment to delete
        $this->client->post('make_appointment.php', [
            'form_params' => [
                'date' => '2024-04-15',
                'time' => '09:00',
                'service' => 'Service',
            ],
        ]);

        // Get the ID of the newly created appointment
        $appointmentsResponse = $this->client->get('show_appointment.php');
        $appointments = json_decode($appointmentsResponse->getBody()->getContents(), true);
        $appointmentId = $appointments[0]['id'];

        // Delete the appointment
        $response = $this->client->post('delete_appointment.php', [
            'form_params' => [
                'id' => $appointmentId,
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Delete appointment request sent successfully'); // Success message for assertion
        $this->assertStringContainsString('Appointment deleted successfully', $response->getBody()->getContents(), 'Appointment deleted successfully'); // Success message for assertion
    }
}
