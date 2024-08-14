<?php
session_start();
include 'db_connection.php';
$con = OpenCon();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['exercise']) && isset($_POST['muscle']) && isset($_POST['day']) && isset($_POST['sets']) && isset($_POST['musclepart'])) {
        $exercise = mysqli_real_escape_string($con, $_POST['exercise']);
        $muscle = mysqli_real_escape_string($con, $_POST['muscle']);
        $day = mysqli_real_escape_string($con, $_POST['day']);
        $sets = mysqli_real_escape_string($con, $_POST['sets']);
        $exvedio = mysqli_real_escape_string($con, $_POST['exvedio']);
        $musclepart = mysqli_real_escape_string($con, $_POST['musclepart']);

        // Assuming user ID is stored in session
        $userId = $_SESSION['userId'];
        $managerQuery = "SELECT * FROM manager WHERE manager_id = '$userId'";
        $managerResult = mysqli_query($con, $managerQuery);
        $managerDataQuery = "SELECT * FROM users WHERE id='$userId'";
        $managerDataResult = mysqli_query($con, $managerDataQuery);

        $username = $_SESSION["Username"];
        if (mysqli_num_rows($managerResult) > 0) {
            $username = $_SESSION['adminaddexforuser'];
        } else {
            $username = $_SESSION["Username"];
        }

        // Check if a similar exercise exists for the same day and muscle
        $checkSql = "SELECT * FROM weeklyexercise WHERE username = '$username' AND musclename = '$muscle' AND day = '$day' AND exercise = '$exercise'";
        $checkResult = mysqli_query($con, $checkSql);

        if (mysqli_num_rows($checkResult) > 0) {
            $message = "You already have this exercise for the same day and muscle.";
            echo "<script type='text/javascript'>
            alert('$message');
                    window.location.href = 'userdata.php';
                  </script>";
        } else {
            // Insert data into the database
            $stmt = $con->prepare("INSERT INTO weeklyexercise (username, exvedio, musclename, musclepart, day, sets, exercise) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $username, $exvedio, $muscle, $musclepart, $day, $sets, $exercise);
            
            if ($stmt->execute()) {
                $message = "Exercise added successfully!";
             
                echo "<script type='text/javascript'>
                        alert('$message');
                        window.location.href = 'userdata.php';
                      </script>";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }

        $user = $_SESSION["Username"];
        if ($user == $username) {
            echo "<script type='text/javascript'>
                    alert('$message');
                    window.location.href = 'exercise_schedule.php';
                  </script>";
        } else {
            $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';
            echo "<script type='text/javascript'>
                    alert('$message');
                    window.location.href = '$previousPage';
                  </script>";
        }
        exit();
    }
}

mysqli_close($con);
?>
