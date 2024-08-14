<?php
session_start();
include 'db_connection.php';
$con = OpenCon(); // Open database connection

$username = $_SESSION['Username'];
$exerciseId = $_POST['exerciseId'];
$day = $_POST['day'];
$currentDay = date('l'); // Get the current day, e.g., "Monday"

// Check if the current day matches the day in the form
if ($day === $currentDay) {
    // Check if a record exists for the user for the current day
    $checkQuery = "SELECT * FROM stats WHERE username = '$username' AND day = '$currentDay'";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Record exists, so increment the 'nowex' field
        $updateQuery = "UPDATE stats SET nowex = nowex + 1 WHERE username = '$username' AND day = '$currentDay'";
        mysqli_query($con, $updateQuery);
    } else {
        // If there's no record for today, insert a new one (optional)
        $insertQuery = "INSERT INTO stats (username, totalex, day, nowex) VALUES ('$username', 0, '$currentDay', 1)";
        mysqli_query($con, $insertQuery);
    }

    // Remove the exercise from the weeklyexercise table
    $stmt = $con->prepare("DELETE FROM weeklyexercise WHERE exercise = ? AND username = ? AND day = ?");
    $stmt->bind_param("sss", $exerciseId, $username, $day);
    $stmt->execute();
    $stmt->close();
} else {
    // If the day does not match, you can handle the situation, e.g., show a message or log it
    $_SESSION['error'] = "You can only mark exercises as done for the current day.";
}

// Close the database connection
CloseCon($con);

// Redirect back to userdata.php or handle any error if needed
header("Location: userdata.php");
exit();
?>
