<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Interaction</title>
</head>
<body>

    <?php
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Simulate some PHP response
        $response = "PHP says: Hello from the server!";
        echo "<h1>$response</h1>";
    }
    ?>

    <form method="post">
        <input type="submit" name="fetchButton" value="Fetch Data from PHP">
    </form>

</body>
</html>
