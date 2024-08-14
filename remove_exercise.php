<?php
session_start();
include 'db_connection.php';
$con = OpenCon(); // Open database connection
$username = $_SESSION['Username'];

if (isset($_POST['exerciseId']) && isset($_POST['day'])) {
    $exerciseId = $_POST['exerciseId'];
    $day = $_POST['day'];

    // Prepare the DELETE statement
    $stmt = $con->prepare("DELETE FROM weeklyexercise WHERE exercise = ? AND username = ? AND day = ?");
    $stmt->bind_param("sss", $exerciseId, $username, $day);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Exercise removed successfully.";
    } else {
        echo "Error removing exercise: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No exercise ID or day provided.";
}

CloseCon($con); // Close database connection

// Redirect back to the previous page
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;
?>
