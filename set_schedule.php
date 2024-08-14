<?php
session_start();
include 'db_connection.php';
$con = OpenCon(); // Open database connection

$username = $_SESSION['Username']; // Retrieve username from session

// Fetch current exercises from exhistory
$exHistoryQuery = "SELECT * FROM exhistory WHERE username='$username' ORDER BY day, time";
$exHistoryResult = mysqli_query($con, $exHistoryQuery);

if (mysqli_num_rows($exHistoryResult) > 0) {
    // Clear current weekly exercises and workout time
    $clearWeeklyExerciseQuery = "DELETE FROM weeklyexercise WHERE username='$username'";
    mysqli_query($con, $clearWeeklyExerciseQuery);
    $clearWorkoutTimeQuery = "DELETE FROM workouttime WHERE username='$username'";
    mysqli_query($con, $clearWorkoutTimeQuery);

    // Track if we've already inserted a workout time for each day
    $daysInserted = array();

    while ($row = mysqli_fetch_assoc($exHistoryResult)) {
        $day = $row['day'];
        $time = $row['time'];
        $exercise = $row['exname'];
        $musclename = $row['mucsle'];
        $sets = $row['sets'];
        $exvedio = $row['exvedio'];
        $musclepart = $row['musclepart'];

        // Insert into weeklyexercise
        $insertWeeklyExerciseQuery = "INSERT INTO weeklyexercise (username, day, exercise, musclename, sets, exvedio, musclepart)
                                      VALUES ('$username', '$day', '$exercise', '$musclename', '$sets', '$exvedio', '$musclepart')";
        mysqli_query($con, $insertWeeklyExerciseQuery);

        // Insert into workouttime only if the day hasn't been inserted yet
        if (!in_array($day, $daysInserted)) {
            $insertWorkoutTimeQuery = "INSERT INTO workouttime (username, day,time)
                                       VALUES ('$username', '$day','$time')";
            mysqli_query($con, $insertWorkoutTimeQuery);
            $daysInserted[] = $day; // Mark that we've inserted this day
        }
    }
    echo "<script>
            alert('Schedule set successfully.');
            window.location.href='userdata.php';
          </script>";
    exit();
 }
  else {
    echo "No exercises found to set.";
}

CloseCon($con); // Close database connection
?>
