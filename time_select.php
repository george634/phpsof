<?php
session_start();
include 'db_connection.php';

$con = OpenCon(); // Open database connection

if (isset($_GET['day']) && isset($_GET['time']) && isset($_SESSION['Username'])) {
    $day = $_GET['day'];
    $time = $_GET['time'];
    $username = $_SESSION['Username'];

    // Validate input
    $day = mysqli_real_escape_string($con, $day);
    $time = mysqli_real_escape_string($con, $time);
    $username = mysqli_real_escape_string($con, $username);

    // Save selected day and time in session
    $_SESSION['selected_day'] = $day;
    $_SESSION['selected_time'] = $time;

    // Check if an entry already exists for the user on the specified day
    $checkQuery = "SELECT * FROM workouttime WHERE username='$username' AND day='$day'";
    $result = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($result) == 0) {
        // Insert new entry
        $insertQuery = "INSERT INTO workouttime (username, day, time) VALUES ('$username', '$day', '$time')";
        if (mysqli_query($con, $insertQuery)) {
            echo "<script>alert('Workout time added successfully to $time'); window.location.href='exercise_schedule.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "'); window.location.href='exercise_schedule.php';</script>";
        }
    } else {
        // Update existing entry
        $updateQuery = "UPDATE workouttime SET time='$time' WHERE username='$username' AND day='$day'";
        if (mysqli_query($con, $updateQuery)) {
            echo "<script>alert('Workout time updated successfully to $time'); window.location.href='exercise_schedule.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "'); window.location.href='exercise_schedule.php';</script>";
        }
    }
}

// Close database connection
CloseCon($con);
?>
