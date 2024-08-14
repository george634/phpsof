<?php
session_start(); // Start or resume the session
include 'db_connection.php';
$con = OpenCon(); // Open database connection

// Validate and sanitize input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate gender
    if (isset($_POST['gender']) && ($_POST['gender'] === 'male' || $_POST['gender'] === 'female')) {
        $_SESSION['gender'] = $_POST['gender']; // Save gender in session
    } else {
        // Handle invalid input scenario
        $_SESSION['gender'] = null; // Or set default value
    }

    // Validate goal choice
    if (isset($_POST['goal']) && ($_POST['goal'] === 'Lose Weight' || $_POST['goal'] === 'Gain Muscles')) {
        $_SESSION['goal'] = $_POST['goal']; // Save goal in session
        
        // Update the user's goal in the database
        if (isset($_SESSION['Username'])) {
            $username = $_SESSION['Username'];
            $goal = mysqli_real_escape_string($con, $_POST['goal']); // Sanitize input

            $updateGoalQuery = "UPDATE users SET goal = '$goal' WHERE username = '$username'";
            if (mysqli_query($con, $updateGoalQuery)) {
                // Goal updated successfully
            } else {
                echo "Error updating goal: " . mysqli_error($con);
            }
        }
    } else {
        // Handle invalid input scenario
        $_SESSION['goal'] = null; // Or set default value
    }

    // Save workout plan if submitted
    if (isset($_POST['workout'])) {
        $_SESSION['workout'] = $_POST['workout'];
    }

    // Save beginner status if selected
    if (isset($_POST['option']) && $_POST['option'] === 'self') {
        $_SESSION['beginner_option'] = true;
    } else {
        $_SESSION['beginner_option'] = false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Workout Plan</title>
    <style>
        /* Your existing CSS styles */
    </style>
    <script>
        function checkTime(day, time) {
            const timeSlots = document.querySelectorAll(`#time-${day} .time-slot`);
            for (let slot of timeSlots) {
                if (slot.textContent.trim() === time) {
                    alert(`You have already selected ${time} for ${day}.`);
                    return false;
                }
            }
            return true;
        }
    </script>
</head>
<body>
<header>
    <div class="container">
        <div id="branding">
            <a href="<?php 
                if (isset($_SESSION['goal']) && $_SESSION['goal'] == 'Gain Muscles') {
                    echo 'gainmuscleworkout.php';
                } elseif (isset($_SESSION['goal']) && $_SESSION['goal'] == 'Lose Weight') {
                    echo 'loseweightworkouts.php';
                } elseif (isset($_SESSION['beginner_option']) && $_SESSION['beginner_option']) {
                    echo 'pageforbeginer.php';
                } else {
                    echo 'userdata.php';
                }
            ?>" class="header-link">
                <h1>Recommended workouts to <?php echo isset($_SESSION['goal']) ? $_SESSION['goal'] : 'Workout Plan'; ?></h1>
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="userdata.php">Back to Workout Plan</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <h1>Weekly Workout Plan</h1>
    <form method="POST">
        <table>
            <tr>
                <th>Day</th>
                <th>Chest</th>
                <th>Back</th>
                <th>Biceps</th>
                <th>Triceps</th>
                <th>Shoulders</th>
                <th>Legs</th>
                <th>Abs</th>
                <th>Time</th>
            </tr>
            <?php 
            $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
            $muscleGroups = ["Chest", "Back", "Biceps", "Triceps", "Shoulders", "Legs", "Abs"];
            
            foreach ($daysOfWeek as $day) {
                echo "<tr>";
                echo "<td>$day</td>";

                foreach ($muscleGroups as $muscle) {
                    if (isset($_SESSION['selected_day']) && $_SESSION['selected_day'] == $day && isset($_SESSION['selected_time'])) {
                        echo "<td><a href='muscle_exirse.php?day=$day&muscle=$muscle' class='button'>$muscle</a></td>";
                    } else {
                        echo "<td><span class='button disabled'>$muscle</span></td>";
                    }
                }

                echo "<td id='time-$day'><a href='time_select.php?day=$day&time=morning' class='button time-slot' onclick='return checkTime(\"$day\", \"morning\")'>Morning</a> <a href='time_select.php?day=$day&time=evening' class='button time-slot' onclick='return checkTime(\"$day\", \"evening\")'>Evening</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </form>
</div>
</body>
</html>

<?php
CloseCon($con); // Close the database connection
?>

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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #35424a;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        a.button {
            display: inline-block;
            color: white;
            background: #e8491d;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        a.button:hover {
            background: #35424a;
        }

        .disabled {
            background: #ddd;
            color: #aaa;
            pointer-events: none;
        }

        .message {
            margin: 20px 0;
            padding: 10px;
            background-color: #e8491d;
            color: white;
            border-radius: 5px;
            text-align: center;
        }
        .header-link h1 {
        color: white;
        text-decoration: none;
        font-size: 20px; /* Adjust the font size as needed */
    }
    
    .header-link:hover h1 {
        color: #e8491d; /* Change to your preferred hover color */
        text-decoration: underline; /* Add underline on hover */
    }
    </style>