<?php
session_start();

include 'db_connection.php';
$con = OpenCon(); // Open database connection

// Initialize session variables if not already set
if (!isset($_SESSION['c'])) {
    $_SESSION['c'] = 0;
}
$username = $_SESSION['Username'];
$currentDayOfWeek = date('l'); // Gets the current day of the week like Monday, Tuesday, etc.

if (!isset($_SESSION['last_update'])) {
    $_SESSION['last_update'] = time();
}

// Check if 24 hours have passed since the last update
if (time() - $_SESSION['last_update'] >= 86400) { // 86400 seconds in a day
    $_SESSION['c']++;
    $_SESSION['last_update'] = time();
}

$exerciseCount = 0;
$selectedExercises = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Save selected option and time in session
    if (isset($_POST['option']) && isset($_POST['time_of_day'])) {
        $_SESSION['beginner_option'] = $_POST['option'] === 'self' ? true : false;
        $_SESSION['time_of_day'] = $_POST['time_of_day'];

        if ($_POST['option'] == 'auto') {
            // Check if exercises have already been inserted for today
            $checkQuery = "SELECT * FROM weeklyexercise WHERE username = '$username' AND day = '$currentDayOfWeek'";
            $checkResult = mysqli_query($con, $checkQuery);

            if (mysqli_num_rows($checkResult) == 0) { // No entries for today
                $muscleParts = [
                    'upper part' => 'upperchest',
                    'middele chest' => 'middlechest',
                    'lower chest' => 'lowerchest',
                    'long head' => 'lpicep',
                    'short head' => 'spicep'
                ];

                foreach ($muscleParts as $musclePart => $partName) {
                    $exerciseQuery = "
                        SELECT a.exname, a.exvedio, a.muscle, a.musclepart
                        FROM allexercise a
                        WHERE a.musclepart = '$musclePart' AND (
                            SELECT COUNT(*) 
                            FROM weeklyexercise w
                            JOIN workouttime t ON w.username = t.username
                            WHERE w.exercise = a.exname 
                              AND w.day = '$currentDayOfWeek' 
                              AND t.time = '{$_SESSION['time_of_day']}'
                        ) < 3
                        ORDER BY RAND() LIMIT 1";

                    $exerciseResult = mysqli_query($con, $exerciseQuery);

                    if ($exerciseResult) {
                        $exercise = mysqli_fetch_assoc($exerciseResult);

                        if ($exercise) {
                            $selectedExercises[$partName] = $exercise;

                            // Insert exercise into weeklyexercise table
                            $exerciseName = $exercise['exname'];
                            $exerciseVideo = $exercise['exvedio'];
                            $muscleName = $exercise['muscle'];
                            $musclePart = $exercise['musclepart'];

                            $insertQuery = "INSERT INTO weeklyexercise (username, exvedio, musclename, musclepart, day, sets, exercise)
                                            VALUES ('$username', '$exerciseVideo', '$muscleName', '$musclePart', '$currentDayOfWeek', 3, '$exerciseName')";
                            mysqli_query($con, $insertQuery);

                            // Insert into workouttime table
                           

                            // Count users assigned to the same exercise on the same day and time
                            $countQuery = "
                                SELECT COUNT(*) as exerciseCount 
                                FROM weeklyexercise w
                                JOIN workouttime t ON w.username = t.username
                                WHERE w.exercise = '$exerciseName' 
                                  AND w.day = '$currentDayOfWeek' 
                                  AND t.time = '{$_SESSION['time_of_day']}'";
                            $countResult = mysqli_query($con, $countQuery);
                            if ($countResult) {
                                $countData = mysqli_fetch_assoc($countResult);
                                $exerciseCount = $countData['exerciseCount'];
                            }
                        }
                    }
                }
                $insertWorkoutTimeQuery = "INSERT INTO workouttime (username, day, time)
                VALUES ('$username', '$currentDayOfWeek', '{$_SESSION['time_of_day']}')";
mysqli_query($con, $insertWorkoutTimeQuery);
            } else {
                echo "You have already added exercises for today. Please try again tomorrow.";
            }
        }
    }
}

$selectedOption = isset($_SESSION['beginner_option']) ? $_SESSION['beginner_option'] : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Beginner Workout Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        header {
            background: #35424a;
            color: #ffffff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #e8491d 3px solid;
        }

        header a {
            color: #ffffff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }

        header ul {
            padding: 0;
            list-style: none;
        }

        header li {
            float: left;
            display: inline;
            padding: 0 20px 0 20px;
        }

        header #branding {
            float: left;
        }

        header #branding h1 {
            margin: 0;
        }

        header nav {
            float: right;
            margin-top: 10px;
        }

        h1, p {
            color: #35424a;
        }

        .button {
            display: inline-block;
            color: white;
            background: #e8491d;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center.
        }

        .button:hover {
            background: #35424a;
        }

        .option-container {
            margin: 20px 0;
            text-align: center;
        }

        .option-container form {
            display: inline-block;
            margin: 0 10px;
        }

        .exercise-list {
            margin: 20px 0;
            padding: 0;
            list-style: none;
        }

        .exercise-list li {
            margin-bottom: 10px;
        }

        .time-selection {
            margin: 20px 0;
            text-align: center.
        }

        .time-selection label {
            font-size: 18px.
        }

        .time-selection input {
            margin: 0 10px.
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Beginner Workout Plan</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="userdata.php">Back to Dashboard</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>Select Your Workout Option</h1>
        <p>You will get a random exercise every day for a week.</p>

        <div class="time-selection">
            <form method="post" action="">
                <label for="morning">Morning</label>
                <input type="radio" id="morning" name="time_of_day" value="morning" required>
                <label for="evening">Evening</label>
                <input type="radio" id="evening" name="time_of_day" value="evening" required>

                <div class="option-container">
                    <!-- <form method="post" action="">
                        <input type="hidden" name="option" value="auto">
                        <button type="submit" class="button">Get Daily Exercise Automatically</button>
                    </form> -->
                    <form method="post" action="">
                        <input type="hidden" name="option" value="admin">
                        <button type="submit" class="button">Let Admin Assign Daily Exercise</button>
                    </form>
                    <form method="post" action="exercise_schedule.php">
                        <input type="hidden" name="option" value="self">
                        <button type="submit" class="button">Create Your Own Exercise Routine</button>
                    </form>
                </div>
            </form>
        </div>

        
    </div>
</body>
</html>
