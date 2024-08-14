<?php
session_start(); // Start or resume the session

include 'db_connection.php';
$con = OpenCon(); // Open database connection

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve the username from the session
$user = $_SESSION['adminaddexforuser'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Clear existing exercise requests for the user
    $deleteSql = "DELETE FROM exerciserequest WHERE username = ?";
    $stmt = $con->prepare($deleteSql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the workout plan page
    echo "<script type='text/javascript'>
            alert('Exercise requests cleared successfully!');
            window.location.href = 'userdata.php';
          </script>";
    exit();
}

$con->close();
?>
