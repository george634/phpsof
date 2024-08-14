<?php
include 'db_connection.php';
$con = OpenCon();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'remove_user' is set and not empty
    if (isset($_POST['remove_user']) && !empty($_POST['remove_user'])) {
        // Sanitize the username before using it in the query
        $username_to_remove = mysqli_real_escape_string($con, $_POST['remove_user']);

        // Prepare and execute the DELETE query
        $sql = "DELETE FROM users WHERE username='$username_to_remove'";
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('User $username_to_remove has been successfully removed.');</script>";
            // Redirect back to whatmangmentcando.php page
            echo "<script>window.location = 'userdata.php';</script>";
            exit; // Stop executing further code        } else {
            echo "<p>Error removing user: " . mysqli_error($con) . "</p>";
        }
    } else {
        // Handle case where 'remove_user' is not set or empty
        echo "<p>No user selected for removal.</p>";
    }
}

// Close the database connection
CloseCon($con);
?>
