<?php
session_start();
include 'db_connection.php';
$con = OpenCon(); // Open database connection

$username = $_SESSION['Username']; // Retrieve the username from the session

// Delete records from exhistory for the current user
$clearExHistoryQuery = "DELETE FROM exhistory WHERE username='$username'";
mysqli_query($con, $clearExHistoryQuery);

$message = "";
if (mysqli_affected_rows($con) > 0) {
    $message = "Schedule cleared successfully.";
} else {
    $message = "No schedule found to clear.";
}

CloseCon($con); // Close database connection

// Redirect back to the previous page or any other page
echo "<script>
alert('$message');
window.location.href='userdata.php';
</script>";
exit();
?>
