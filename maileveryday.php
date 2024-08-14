<?php
include 'db_connection.php';
$conn = OpenCon();
// Create connection

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current day
$current_day = date('l'); // Returns the full textual representation of the day (e.g., Monday, Tuesday)

// Determine if the current time is morning or evening
$current_hour = date('H'); // Returns the hour in 24-hour format
if ($current_hour < 12) {
    $current_time = 'morning';
} else {
    $current_time = 'evening';
}

// Fetch email addresses and exercise details of users who train on the current day and time
$sql = "SELECT users.email, weeklyexercise.day, weeklyexercise.exercise, weeklyexercise.musclename, weeklyexercise.musclepart, weeklyexercise.sets, workouttime.time 
        FROM weeklyexercise 
        JOIN users ON weeklyexercise.username = users.username 
        JOIN workouttime ON weeklyexercise.username = workouttime.username
        WHERE weeklyexercise.day = '$current_day' AND workouttime.time = '$current_time'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Email configuration
    $subject = "Your Exercise Update for $current_day $current_time";
    $headers = "From: GTFitness@gmail.com";

    // Send email to each user
    while($row = $result->fetch_assoc()) {
        $to = $row["email"];
        $time_of_day = ucfirst($row["time"]); // Capitalize the first letter of 'morning' or 'evening'
        $body = "Hello, \n\nHere is your exercise update for $current_day $time_of_day:\n";
        $body .= "Exercise: " . $row["exercise"] . "\n";
        $body .= "Muscle: " . $row["musclename"] . "\n";
        $body .= "Part: " . $row["musclepart"] . "\n";
        $body .= "Sets: " . $row["sets"] . "\n\n";
        $body .= "Best regards,\nYour Fitness Team";

        if(mail($to, $subject, $body, $headers)) {
            echo "Email sent successfully to " . $to . "<br>";
        } else {
            echo "Failed to send email to " . $to . "<br>";
        }
    }
} else {
    echo "No users found for $current_day $current_time.";
}

$conn->close();
?>
