<?php
  
require 'vendor/autoload.php'; // Adjust the path based on your project structure

use RedBeanPHP\R;

// Set up database connection
R::setup('mysql:host=sql11.freemysqlhosting.net;dbname=sql11681307', 'sql11681307', 'Y8LhenD1Xp');

if (!R::testConnection()) {
    die('Could not connect to the database. Check your connection settings.');
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Find the user by username
    $user = R::findOne('users', 'username = ?', [$username]);

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user->password)) {
        // Authentication successful, generate JavaScript code
        echo '<script>
      
        var name = "' . $user->name . '"; 
        localStorage.setItem("name", name);

       
       
        window.location.href="index.html";
        </script>';
        exit();
    } else {
        // Authentication failed, show an error message or redirect to the login page
      
       echo '<script> 
     
      window.location.href="Sign_in.html";
       alert("Wrong Username/Password");
      
       </script>';
     
    }
}
?>

<!-- Rest of your HTML code -->
