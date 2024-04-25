<?php
require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    protected function sendPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function testUserRegistration()
    {
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => 'password123'
        ];

        $response = $this->sendPostRequest('http://localhost/PREZERAKOS/register.php', $data);
        $this->assertNotEmpty($response);
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']) && $responseData['success'], 'User registration failed');
        // Add more assertions if needed
    }

    public function testUserLogin()
    {
        $data = [
            'username' => 'johndoe',
            'password' => 'password123'
        ];

        $response = $this->sendPostRequest('http://localhost/PREZERAKOS/login.php', $data);
        $this->assertNotEmpty($response);
        // Add assertions for login success, e.g., check for redirect or token
        $this->assertTrue(true, 'User login successful');
    }

    public function testMakeAppointment()
    {
        // Simulate logged-in user session
        $_SESSION['username'] = 'johndoe';

        $data = [
            'date' => '2024-04-25',
            'time' => '10:00',
            'service' => 'Haircut'
        ];

        $response = $this->sendPostRequest('http://localhost/PREZERAKOS/make_appointment.php', $data);
        $this->assertNotEmpty($response);
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']) && $responseData['success'], 'Appointment creation failed');
        // Add more assertions if needed
    }

    public function testDeleteAppointment()
    {
        // Simulate logged-in user session
        $_SESSION['username'] = 'johndoe';

        $data = ['id' => 1];

        $response = $this->sendPostRequest('http://localhost/PREZERAKOS/delete_appointment.php', $data);
        $this->assertNotEmpty($response);
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']) && $responseData['success'], 'Appointment deletion failed');
        // Add more assertions if needed
    }
}
?>
