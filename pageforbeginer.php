<?php
session_start(); // Start or resume the session

include 'db_connection.php';
$con = OpenCon(); // Open database connection

// Handle POST request and update goal in the session and database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check for the beginner option
    if (isset($_POST['beginner']) && $_POST['beginner'] === 'yes') {
        $_SESSION['goal'] = 'Beginner';
        $goal = 'Beginner'; // Set the goal as "Beginner"
    }
    // Check for the regular goals (Lose Weight or Gain Muscles)
    elseif (isset($_POST['goal']) && ($_POST['goal'] === 'Lose Weight' || $_POST['goal'] === 'Gain Muscles')) {
        $_SESSION['goal'] = $_POST['goal']; // Save goal in session
        $goal = mysqli_real_escape_string($con, $_POST['goal']); // Sanitize input
    } else {
        // Handle invalid input scenario
        $_SESSION['goal'] = null;
        $goal = null;
    }

    // If the goal is set, update the user's goal in the database
    if ($goal !== null && isset($_SESSION['Username'])) {
        $username = $_SESSION['Username'];
        $updateGoalQuery = "UPDATE users SET goal = '$goal' WHERE username = '$username'";
        if (mysqli_query($con, $updateGoalQuery)) {
            // Goal updated successfully
            echo "<script>alert('Goal updated to $goal successfully.');</script>";
        } else {
            echo "Error updating goal: " . mysqli_error($con);
        }
    }
}

// Optional: Handle any other POST actions such as saving options
if (isset($_POST['option'])) {
    $_SESSION['beginner_option'] = $_POST['option'] === 'self' ? true : false;
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

        h1, h2 {
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
            text-align: center;
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
        <div class="option-container">
            <!-- <form method="post" action="test time.php">
                <input type="hidden" name="option" value="auto">
                <button type="submit" class="button">Get Daily Exercise Automatically</button>
            </form> -->
            <form method="post" action="letadminmakeex.php">
                <input type="hidden" name="option" value="admin">
                <button type="submit" class="button">Let Admin Assign Daily Exercise</button>
            </form>
            <form method="post" action="exercise_schedule.php">
                <input type="hidden" name="option" value="self">
                <button type="submit" class="button">Create Your Own Exercise Routine</button>
            </form>
        </div>

        <?php
        if ($selectedOption === true) {
            echo "<h2>Your Daily Exercise</h2>";
            // Generate and display the daily exercise routine here
            
        } elseif ($selectedOption === 'admin') {
            echo "<h2>Admin Assigned Exercise</h2>";
            // Inform the user that the admin will assign their exercise routine
            echo "<p>Please wait for the admin to assign your daily exercise routine.</p>";
        } elseif ($selectedOption === 'self') {
            // Redirect to workout plan creation page
            header("Location: create_workout_plan.php");
            exit();
        }
        ?>
    </div>
</body>
</html>
