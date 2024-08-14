<?php
session_start(); // Start or resume the session

include 'db_connection.php';
$con = OpenCon(); // Open database connection

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$user = $_SESSION["Username"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'clear') {
        // Clear the user's data from the workouttime table
        $deleteWorkoutStmt = $con->prepare("DELETE FROM workouttime WHERE username = ?");
        $deleteWorkoutStmt->bind_param("s", $user);
        $deleteWorkoutStmt->execute();
        $deleteWorkoutStmt->close();

        // Clear the user's data from the exerciserequest table
        $deleteRequestStmt = $con->prepare("DELETE FROM exerciserequest WHERE username = ?");
        $deleteRequestStmt->bind_param("s", $user);
        $deleteRequestStmt->execute();
        $deleteRequestStmt->close();

        echo "<script>alert('Your workout schedule and exercise request have been cleared.');</script>";
    } elseif (isset($_POST['day']) && isset($_POST['time']) && $user !== null) {
        $day = $_POST['day'];
        $time = $_POST['time'];

        // Check if entry exists
        $checkStmt = $con->prepare("SELECT * FROM workouttime WHERE username = ? AND day = ?");
        $checkStmt->bind_param("ss", $user, $day);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing entry
            $updateStmt = $con->prepare("UPDATE workouttime SET time = ? WHERE username = ? AND day = ?");
            $updateStmt->bind_param("sss", $time, $user, $day);
            $updateStmt->execute();
            $updateStmt->close();
        } else {
            // Insert new entry
            $insertStmt = $con->prepare("INSERT INTO workouttime (username, day, time) VALUES (?, ?, ?)");
            $insertStmt->bind_param("sss", $user, $day, $time);
            $insertStmt->execute();
            $insertStmt->close();
        }

        $checkStmt->close();
    }
    if (isset($_POST['action']) && $_POST['action'] == 'done') {
        // Check if the username already exists in the exerciserequest table
        $checkRequestStmt = $con->prepare("SELECT * FROM exerciserequest WHERE username = ?");
        $checkRequestStmt->bind_param("s", $user);
        $checkRequestStmt->execute();
        $requestResult = $checkRequestStmt->get_result();

        if ($requestResult->num_rows > 0) {
            // Username already exists in the exerciserequest table
            echo "<script>alert('You have already submitted your exercise request.'); window.location.href = 'userdata.php';</script>";
        } else {
            // Add the username to the exerciserequest table
            $exerciseRequestStmt = $con->prepare("INSERT INTO exerciserequest (username) VALUES (?)");
            $exerciseRequestStmt->bind_param("s", $user);
            $exerciseRequestStmt->execute();
            $exerciseRequestStmt->close();

            echo "<script>alert('Your exercise schedule will be ready as soon as possible.'); window.location.href = 'userdata.php';</script>";
        }

        $checkRequestStmt->close();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Workout Schedule</title>
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

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .buttons button {
            padding: 10px 20px;
            background-color: #28a745;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .buttons button:hover {
            background-color: #218838;
        }

        .buttons button.cancel {
            background-color: #dc3545;
        }

        .buttons button.cancel:hover {
            background-color: #c82333;
        }

        h2 {
            color: white;
        }
    </style>
</head>
<body>

<div class="container">

    <header>
        <div class="container">
            <div id="branding">
                <h2>Weekly Workout Schedule</h2>
                <h1>Workout Plan</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="<?php echo isset($_SESSION['beginner_option']) && $_SESSION['beginner_option'] ? 'pageforbeginer.php' : 'userdata.php'; ?>">Back to Workout Plan</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <?php
    if ($user === null) {
        echo "<p>Please log in to access your workout schedule.</p>";
    } else {
        ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" id="time" name="time" value="">
            <input type="hidden" id="action" name="action" value="">
            <table>
                <tr>
                    <th>Day</th>
                    <th>Morning</th>
                    <th>Evening</th>
                </tr>
                <?php
                $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

                foreach ($daysOfWeek as $day) {
                    echo "<tr>";
                    echo "<td>$day</td>";

                    // Check if the user has already selected a time for this day
                    $checkStmt = $con->prepare("SELECT time FROM workouttime WHERE username = ? AND day = ?");
                    $checkStmt->bind_param("ss", $user, $day);
                    $checkStmt->execute();
                    $result = $checkStmt->get_result();
                    $existingTime = null;

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $existingTime = $row['time'];
                    }
                    $checkStmt->close();

                    if ($existingTime == 'Morning') {
                        echo "<td><button type='button' disabled>Morning</button></td>";
                    } else {
                        echo "<td><button type='submit' name='day' value='$day' onclick=\"document.getElementById('time').value='Morning'\">Morning</button></td>";
                    }

                    if ($existingTime == 'Evening') {
                        echo "<td><button type='button' disabled>Evening</button></td>";
                    } else {
                        echo "<td><button type='submit' name='day' value='$day' onclick=\"document.getElementById('time').value='Evening'\">Evening</button></td>";
                    }

                    echo "</tr>";
                }
                ?>
            </table>
            <div class="buttons">
                <button type="button" onclick="submitForm('done')">Done</button>
                <button type="button" class="cancel" onclick="submitForm('clear')">Clear</button>
            </div>
        </form>

        <script>
            function submitForm(action) {
                document.getElementById('action').value = action;
                document.forms[0].submit();
            }
        </script>

        <?php
    }
    ?>

</div>

</body>
</html>

<?php
$con->close();
?>
