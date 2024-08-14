<?php
session_start(); // Start or resume the session

include 'db_connection.php';
$con = OpenCon(); // Open database connection

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve the username from the URL
$user = isset($_GET['username']) ? $_GET['username'] : '';
$_SESSION['adminaddexforuser'] = $user;
echo $_SESSION['adminaddexforuser'];

$workoutTime = [];

if ($user) {
    // Fetch the workout time for the selected user
    $sql = "SELECT day, time FROM workouttime WHERE username = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $workoutTime[$row['day']] = $row['time'];
    }

    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Workout Plan</title>
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
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Workout Plan</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="<?php echo isset($_SESSION['beginner_option']) && $_SESSION['beginner_option'] ? 'pageforbeginer.php' : 'userdata.php'; ?>">Back to Workout Plan</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>Weekly Workout Plan for <?php echo htmlspecialchars($user); ?></h1>
        <form method="POST" action="save_workout_schedule.php">
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
                        echo "<td><a href='muscle_exirse.php?day=$day&muscle=$muscle' class='button'>$muscle</a></td>";
                    }

                    $time = isset($workoutTime[$day]) ? $workoutTime[$day] : '';
                    echo "<td>$time</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <button type="submit" class="button">Save Workout Schedule</button>
        </form>
        <ul>
            <!-- <li><a href="<?php echo isset($_SESSION['beginner_option']) && $_SESSION['beginner_option'] ? 'pageforbeginer.php' : 'userdata.php'; ?>" class="button">Back to Workout Plan</a></li> -->
        </ul>
    </div>
</body>
</html>
