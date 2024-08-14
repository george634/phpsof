<?php
include 'db_connection.php';

// Open database connection
$con = OpenCon();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $username = isset($_POST['username']) ? mysqli_real_escape_string($con, $_POST['username']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($con, $_POST['password']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : '';
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($con, $_POST['phone']) : '';
    $date_of_birth = isset($_POST['date_of_birth']) ? mysqli_real_escape_string($con, $_POST['date_of_birth']) : '';
    $Fname = isset($_POST['Fname']) ? mysqli_real_escape_string($con, $_POST['Fname']) : '';
    $Lname = isset($_POST['Lname']) ? mysqli_real_escape_string($con, $_POST['Lname']) : '';
    $id = isset($_POST['id']) ? mysqli_real_escape_string($con, $_POST['id']) : '';

    // Validate and sanitize form data
    // You may add further validation here
    
    // Check if user number is a valid integer and not empty
    if (!empty($id) && is_numeric($id) && intval($id) > 0) {
        // Check if passwords match and meet length requirement
        if (strlen($password) < 8) {
            echo "<p>Password should be at least 8 characters long.</p>";
        } else {
            // Check if username already exists
            $query = "SELECT * FROM users WHERE Username='$username'";
            $result = mysqli_query($con, $query);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<p>Username already exists. Please choose a different one.</p>";
                } else {
                    // Insert new user into the database
                    $insert_query = "INSERT INTO users (Username, Password, firstname, lastname, email, phone, birthday, id, looked, login_attempts) 
                                    VALUES ('$username', '$password', '$Fname', '$Lname', '$email', '$phone', '$date_of_birth', '$id', 1, 0)";

                    if (mysqli_query($con, $insert_query)) {
                        echo "<script>alert('User added successfully.');</script>";
                        // Redirect back to whatmangementcando page
                        echo "<script>window.location = 'userdata.php';</script>";
                        exit;                
                      } else {
                        echo "<p>Error adding user. Please try again.</p>";
                        error_log("Error adding user: " . mysqli_error($con));
                    }
                }
            } else {
                echo "<p>Error executing query: " . mysqli_error($con) . "</p>";
            }
        }
    } else {
        echo "<p>Please enter a valid user ID.</p>";
    }

    // Close database connection
    CloseCon($con);
}
?>