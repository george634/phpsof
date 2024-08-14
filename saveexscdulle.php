<?php
session_start();
include 'db_connection.php';
$con = OpenCon(); // Open database connection

$username = $_POST['username'];
$_SESSION['username'] = $username;

$x = "SELECT goal FROM users WHERE username='$username'";
$result = mysqli_query($con, $x);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $goal = $row['goal'];

    if ($goal == 'beginner') {
        $exerciseData = [];
        $missingDays = [];
        $missingExercises = [];
        
        try {
            // Fetch all days and the number of exercises per day for the user
            $stmt = $con->prepare("
                SELECT 
                    day, COUNT(exercise) AS exercises_per_day
                FROM 
                    weeklyexercise
                WHERE 
                    username = ?
                GROUP BY 
                    day;
            ");
        
            // Bind the username parameter
            $stmt->bind_param("s", $username);
        
            // Execute the query
            $stmt->execute();
        
            // Fetch the results and store in the array
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {
                $exerciseData[] = $row; // Store each day's data
            }
        
            $stmt->close();
        
            // Check each day for missing exercises or days
            foreach ($exerciseData as $data) {
                if ($data['exercises_per_day'] < 6) {
                    $missingExercises[$data['day']] = 6 - $data['exercises_per_day'];
                }
            }
        
            // Calculate the number of days with at least 6 exercises
            $daysWithEnoughExercises = count($exerciseData) - count($missingExercises);
        
            // Determine if the user meets the beginner criteria
            if ($daysWithEnoughExercises >= 5) {
                // Step 1: Delete existing records for the user in the exhistory table
$deleteHistoryQuery = "DELETE FROM exhistory WHERE username='$username'";
mysqli_query($con, $deleteHistoryQuery);

// Step 2: Delete existing records in the stats table for this user
$deleteStatsQuery = "DELETE FROM stats WHERE username='$username'";
mysqli_query($con, $deleteStatsQuery);

// Fetch current weekly exercises
$weeklyExerciseQuery = "SELECT we.day, we.exercise, we.musclename, we.sets, we.exvedio, we.musclepart
                        FROM weeklyexercise we
                        JOIN workouttime wt ON we.username = wt.username AND we.day = wt.day
                        WHERE we.username = '$username'
                        ORDER BY we.day";
$weeklyExerciseResult = mysqli_query($con, $weeklyExerciseQuery);

$newExercisesAdded = false;
$exerciseCounts = []; // Array to hold the count of exercises per day

if (mysqli_num_rows($weeklyExerciseResult) > 0) {
    while ($row = mysqli_fetch_assoc($weeklyExerciseResult)) {
        $day = $row['day'];
        $exercise = $row['exercise'];
        $musclename = $row['musclename'];
        $sets = $row['sets'];
        $exvedio = $row['exvedio'];
        $musclepart = $row['musclepart']; // Ensure this column exists in your table

        // Insert into exhistory table
        $insertQuery = "INSERT INTO exhistory (username, day, time, exname, mucsle, sets, exvedio, musclepart)
                        VALUES ('$username', '$day', (SELECT time FROM workouttime WHERE username='$username' AND day='$day'), '$exercise', '$musclename', '$sets', '$exvedio', '$musclepart')";
        mysqli_query($con, $insertQuery);
        $newExercisesAdded = true;

        // Count exercises per day
        if (!isset($exerciseCounts[$day])) {
            $exerciseCounts[$day] = 0;
        }
        $exerciseCounts[$day]++;
    }

    // Step 3: Insert the count of exercises per day into the stats table
    foreach ($exerciseCounts as $day => $totalExercises) {
        $insertStatsQuery = "INSERT INTO stats (username, day, totalex) VALUES ('$username', '$day', '$totalExercises')";
        mysqli_query($con, $insertStatsQuery);
    }

    if ($newExercisesAdded) {
        echo "<script>
                alert('Schedule saved successfully.');
                window.location.href = 'userdata.php';
              </script>";
    } else {
        echo "<script>
                alert('No new exercises found to save.');
                window.location.href = 'userdata.php';
              </script>";
    }
} else {
    echo "<script>
            alert('No exercises found to save.');
            window.location.href = 'userdata.php';
          </script>";
}

CloseCon($con); // Close database connection
                echo "<script>alert('User $username is a beginner and meets the exercise criteria.'); window.location.href = 'userdata.php';</script>";
            } else {
                echo "<script>alert('You must have at least 5 days with at least 6 exercises per day because you are a beginner.'); window.location.href = 'userdata.php';</script>";
            }
        
            // If user does not meet criteria, also print what is missing
            if ($daysWithEnoughExercises < 5) {
                // Generate the missing details message
                $missingDetails = "";
        
                // Check for missing days
                $requiredDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $userDays = array_column($exerciseData, 'day');
                $missingDays = array_diff($requiredDays, $userDays);
        
                // Print missing exercises for days with insufficient exercises
                foreach ($missingExercises as $day => $missing) {
                    $missingDetails .= "Day: $day is missing $missing exercise(s) to meet the 6 exercises per day requirement.\\n";
                }
        
                // Show alert with missing details
                if (!empty($missingDetails)) {
                    echo "<script>alert('You must have at least 5 days with at least 6 exercises per day because you are a beginner.\\n\\n$missingDetails'); window.location.href = 'userdata.php';</script>";
                }
            }
        
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'userdata.php';</script>";
        }
        
    } elseif ($goal == 'Gain Muscles') {
        // Logic for users whose goal is to build muscle
        $exerciseData = [];
        $missingDays = [];
        $missingExercises = [];
        
        try {
            // Fetch all days and the number of exercises per day for the user
            $stmt = $con->prepare("
                SELECT 
                    day, COUNT(exercise) AS exercises_per_day
                FROM 
                    weeklyexercise
                WHERE 
                    username = ?
                GROUP BY 
                    day;
            ");
        
            // Bind the username parameter
            $stmt->bind_param("s", $username);
        
            // Execute the query
            $stmt->execute();
        
            // Fetch the results and store in the array
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {
                $exerciseData[] = $row; // Store each day's data
            }
        
            $stmt->close();
        
            // Check each day for missing exercises or days
            foreach ($exerciseData as $data) {
                if ($data['exercises_per_day'] < 6) {
                    $missingExercises[$data['day']] = 6 - $data['exercises_per_day'];
                }
            }
        
            // Calculate the number of days with at least 6 exercises
            $daysWithEnoughExercises = count($exerciseData) - count($missingExercises);
        
            // Determine if the user meets the muscle-building criteria
            if ($daysWithEnoughExercises >= 3) {
                // Step 1: Delete existing records for the user in the exhistory table
$deleteHistoryQuery = "DELETE FROM exhistory WHERE username='$username'";
mysqli_query($con, $deleteHistoryQuery);

// Step 2: Delete existing records in the stats table for this user
$deleteStatsQuery = "DELETE FROM stats WHERE username='$username'";
mysqli_query($con, $deleteStatsQuery);

// Fetch current weekly exercises
$weeklyExerciseQuery = "SELECT we.day, we.exercise, we.musclename, we.sets, we.exvedio, we.musclepart
                        FROM weeklyexercise we
                        JOIN workouttime wt ON we.username = wt.username AND we.day = wt.day
                        WHERE we.username = '$username'
                        ORDER BY we.day";
$weeklyExerciseResult = mysqli_query($con, $weeklyExerciseQuery);

$newExercisesAdded = false;
$exerciseCounts = []; // Array to hold the count of exercises per day

if (mysqli_num_rows($weeklyExerciseResult) > 0) {
    while ($row = mysqli_fetch_assoc($weeklyExerciseResult)) {
        $day = $row['day'];
        $exercise = $row['exercise'];
        $musclename = $row['musclename'];
        $sets = $row['sets'];
        $exvedio = $row['exvedio'];
        $musclepart = $row['musclepart']; // Ensure this column exists in your table

        // Insert into exhistory table
        $insertQuery = "INSERT INTO exhistory (username, day, time, exname, mucsle, sets, exvedio, musclepart)
                        VALUES ('$username', '$day', (SELECT time FROM workouttime WHERE username='$username' AND day='$day'), '$exercise', '$musclename', '$sets', '$exvedio', '$musclepart')";
        mysqli_query($con, $insertQuery);
        $newExercisesAdded = true;

        // Count exercises per day
        if (!isset($exerciseCounts[$day])) {
            $exerciseCounts[$day] = 0;
        }
        $exerciseCounts[$day]++;
    }

    // Step 3: Insert the count of exercises per day into the stats table
    foreach ($exerciseCounts as $day => $totalExercises) {
        $insertStatsQuery = "INSERT INTO stats (username, day, totalex) VALUES ('$username', '$day', '$totalExercises')";
        mysqli_query($con, $insertStatsQuery);
    }

    if ($newExercisesAdded) {
        echo "<script>
                alert('Schedule saved successfully.');
                window.location.href = 'userdata.php';
              </script>";
    } else {
        echo "<script>
                alert('No new exercises found to save.');
                window.location.href = 'userdata.php';
              </script>";
    }
} else {
    echo "<script>
            alert('No exercises found to save.');
            window.location.href = 'userdata.php';
          </script>";
}

CloseCon($con); // Close database connection
                echo "<script>alert('User $username has a goal to build muscle and meets the criteria.'); window.location.href = 'userdata.php';</script>";
            } else {
                // Generate the missing details message
                $missingDetails = "";
        
                // Check for missing days
                $requiredDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $userDays = array_column($exerciseData, 'day');
                $missingDays = array_diff($requiredDays, $userDays);
        
                // Print missing exercises for days with insufficient exercises
                foreach ($missingExercises as $day => $missing) {
                    $missingDetails .= "Day: $day is missing $missing exercise(s) to meet the 6 exercises per day requirement.\\n";
                }
        
                // Show alert with missing details
                if (!empty($missingDetails)) {
                    echo "<script>alert('You must have at least 3 days with at least 6 exercises per day to build muscle.\\n\\n$missingDetails'); window.location.href = 'userdata.php';</script>";
                }
            }
        
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'userdata.php';</script>";
        }
    } elseif ($goal == 'Lose Weight') {
        // Logic for users whose goal is to build muscle
        $exerciseData = [];
        $missingDays = [];
        $missingExercises = [];
        
        try {
            // Fetch all days and the number of exercises per day for the user
            $stmt = $con->prepare("
                SELECT 
                    day, COUNT(exercise) AS exercises_per_day
                FROM 
                    weeklyexercise
                WHERE 
                    username = ?
                GROUP BY 
                    day;
            ");
        
            // Bind the username parameter
            $stmt->bind_param("s", $username);
        
            // Execute the query
            $stmt->execute();
        
            // Fetch the results and store in the array
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {
                $exerciseData[] = $row; // Store each day's data
            }
        
            $stmt->close();
        
            // Check each day for missing exercises or days
            foreach ($exerciseData as $data) {
                if ($data['exercises_per_day'] < 6) {
                    $missingExercises[$data['day']] = 6 - $data['exercises_per_day'];
                }
            }
        
            // Calculate the number of days with at least 6 exercises
            $daysWithEnoughExercises = count($exerciseData) - count($missingExercises);
        
            // Determine if the user meets the muscle-building criteria
            if ($daysWithEnoughExercises >= 3) {
// Step 1: Delete existing records for the user in the exhistory table
$deleteHistoryQuery = "DELETE FROM exhistory WHERE username='$username'";
mysqli_query($con, $deleteHistoryQuery);

// Step 2: Delete existing records in the stats table for this user
$deleteStatsQuery = "DELETE FROM stats WHERE username='$username'";
mysqli_query($con, $deleteStatsQuery);

// Fetch current weekly exercises
$weeklyExerciseQuery = "SELECT we.day, we.exercise, we.musclename, we.sets, we.exvedio, we.musclepart
                        FROM weeklyexercise we
                        JOIN workouttime wt ON we.username = wt.username AND we.day = wt.day
                        WHERE we.username = '$username'
                        ORDER BY we.day";
$weeklyExerciseResult = mysqli_query($con, $weeklyExerciseQuery);

$newExercisesAdded = false;
$exerciseCounts = []; // Array to hold the count of exercises per day

if (mysqli_num_rows($weeklyExerciseResult) > 0) {
    while ($row = mysqli_fetch_assoc($weeklyExerciseResult)) {
        $day = $row['day'];
        $exercise = $row['exercise'];
        $musclename = $row['musclename'];
        $sets = $row['sets'];
        $exvedio = $row['exvedio'];
        $musclepart = $row['musclepart']; // Ensure this column exists in your table

        // Insert into exhistory table
        $insertQuery = "INSERT INTO exhistory (username, day, time, exname, mucsle, sets, exvedio, musclepart)
                        VALUES ('$username', '$day', (SELECT time FROM workouttime WHERE username='$username' AND day='$day'), '$exercise', '$musclename', '$sets', '$exvedio', '$musclepart')";
        mysqli_query($con, $insertQuery);
        $newExercisesAdded = true;

        // Count exercises per day
        if (!isset($exerciseCounts[$day])) {
            $exerciseCounts[$day] = 0;
        }
        $exerciseCounts[$day]++;
    }

    // Step 3: Insert the count of exercises per day into the stats table
    foreach ($exerciseCounts as $day => $totalExercises) {
        $insertStatsQuery = "INSERT INTO stats (username, day, totalex) VALUES ('$username', '$day', '$totalExercises')";
        mysqli_query($con, $insertStatsQuery);
    }

    if ($newExercisesAdded) {
        echo "<script>
                alert('Schedule saved successfully.');
                window.location.href = 'userdata.php';
              </script>";
    } else {
        echo "<script>
                alert('No new exercises found to save.');
                window.location.href = 'userdata.php';
              </script>";
    }
} else {
    echo "<script>
            alert('No exercises found to save.');
            window.location.href = 'userdata.php';
          </script>";
}

CloseCon($con); // Close database connection



                echo "<script>alert('User $username has a goal to lose weight and meets the criteria.'); window.location.href = 'userdata.php';</script>";
            } else {
                // Generate the missing details message
                $missingDetails = "";
        
                // Check for missing days
                $requiredDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $userDays = array_column($exerciseData, 'day');
                $missingDays = array_diff($requiredDays, $userDays);
        
                // Print missing exercises for days with insufficient exercises
                foreach ($missingExercises as $day => $missing) {
                    $missingDetails .= "Day: $day is missing $missing exercise(s) to meet the 6 exercises per day requirement.\\n";
                }
        
                // Show alert with missing details
                if (!empty($missingDetails)) {
                    echo "<script>alert('You must have at least 3 days with at least 6 exercises per day to build muscle.\\n\\n$missingDetails'); window.location.href = 'userdata.php';</script>";
                }
            }
        
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'userdata.php';</script>";
        }
        echo "<script>alert('User $username has a goal to lose weight. Implement your specific logic here.'); window.location.href = 'userdata.php';</script>";
    } else {
        // Handle any other goals or unexpected values
        echo "<script>alert('User $username has an unspecified goal.'); window.location.href = 'userdata.php';</script>";
    }

} else {
    echo "<script>alert('No goal found for user $username.'); window.location.href = 'userdata.php';</script>";
}

// Initialize arrays to store exercise data and missing data

CloseCon($con); // Close database connection

?>
