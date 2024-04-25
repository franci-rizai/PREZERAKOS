<?php
require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    // Test user registration
    public function testUserRegistration()
    {
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => 'password123'
        ];

        $response = $this->sendPostRequest('register.php', $data);
        $this->assertNotEmpty($response);
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']) && $responseData['success']);
        // Add more assertions if needed
    }

    // Test user login
    public function testUserLogin()
    {
        $data = [
            'username' => 'johndoe',
            'password' => 'password123'
        ];

        $response = $this->sendPostRequest('login.php', $data);
        $this->assertNotEmpty($response);
        // Add assertions for login success, e.g., check for redirect or token
    }

    // Test appointment creation
    public function testMakeAppointment()
    {
        // Ensure the user is logged in first
        $_SESSION['username'] = 'johndoe';

        $data = [
            'date' => '2024-04-25',
            'time' => '10:00',
            'service' => 'Haircut'
        ];

        $response = $this->sendPostRequest('make_appointment.php', $data);
        $this->assertNotEmpty($response);
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']) && $responseData['success']);
        // Add more assertions if needed
    }

    // Test appointment deletion
    public function testDeleteAppointment()
    {
        // Ensure the user is logged in first
        $_SESSION['username'] = 'johndoe';

        // Assume there is an appointment ID to delete
        $data = ['id' => 1];

        $response = $this->sendPostRequest('delete_appointment.php', $data);
        $this->assertNotEmpty($response);
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']) && $responseData['success']);
        // Add more assertions if needed
    }


    // Helper function to send POST requests
    private function sendPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
?>
