<?php
session_start();
include 'db_connection.php';

$con = OpenCon(); // Open database connection
if (isset($_SESSION['percentage'])) {
   
    if ($_SESSION['percentage'] == 100) {
        $percentage=$_SESSION['percentage'];
        $currentDate = date('Y-m-d'); 
        $username=$_SESSION['Username'];
        $day =  date('l');
        // Clear all previous entries for the user in finalstats
        $clearfstatsQuery = "DELETE FROM fainalstats WHERE username = '$username'";
        mysqli_query($con, $clearfstatsQuery);
    
        // Insert the new data into the finalstats table
        $insertQuery = "INSERT INTO fainalstats (date, username, points) VALUES ('$currentDate', '$username', '$percentage')";
        if (mysqli_query($con, $insertQuery)) {
            
            // Clear all data for the user for the current day
            $clearQuery = "DELETE FROM weeklyexercise WHERE username = '$username' AND day = '$day'";
            mysqli_query($con, $clearQuery);
    
            $clearWorkoutTimeQuery = "DELETE FROM workouttime WHERE username = '$username' AND day = '$day'";
            mysqli_query($con, $clearWorkoutTimeQuery);
    
            $clearStatsQuery = "DELETE FROM stats WHERE username = '$username' AND day = '$day'";
            mysqli_query($con, $clearStatsQuery);
    
            echo "<script>
                alert('all good.');
                window.location.href = 'userdata.php';
            </script>";
        } else {
            echo "<script>
                alert('Error: " . mysqli_error($con) . "');
                window.location.href = 'userdata.php';
            </script>";
        }
    }else{
        $username = $_POST['username'];
        $day = $_POST['day'];
        $percentage = $_POST['percentage'];
        $currentDate = date('Y-m-d'); // Get the current date in 'YYYY-MM-DD' format
        $clearfstatsQuery = "DELETE FROM fainalstats WHERE username = '$username' AND date =' $currentDate ' ";
            mysqli_query($con, $clearfstatsQuery);
        // Insert the data into the finalstats table
        $insertQuery = "INSERT INTO fainalstats (date, username, points) VALUES ('$currentDate', '$username', '$percentage')";
        if (mysqli_query($con, $insertQuery)) {
            // Clear all data for the user for the current day
            $clearQuery = "DELETE FROM weeklyexercise WHERE username = '$username' AND day = '$day'";
            mysqli_query($con, $clearQuery);
        
            $clearWorkoutTimeQuery = "DELETE FROM workouttime WHERE username = '$username' AND day = '$day'";
            mysqli_query($con, $clearWorkoutTimeQuery);
        
            $clearStatsQuery = "DELETE FROM stats WHERE username = '$username' AND day = '$day'";
            mysqli_query($con, $clearStatsQuery);
        
            echo "<script>
            alert('Your exercises for today have been marked as done and cleared.');
            window.location.href = 'userdata.php';
        </script>";}
         else {
            echo "<script>
            alert('Error: " . mysqli_error($con) . "');
            window.location.href = 'userdata.php';
        </script>";}
        
        CloseCon($con); // Close database connection
    }
}

// Get data from the form submission

?>
