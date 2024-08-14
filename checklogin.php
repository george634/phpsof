<!DOCTYPE html>
<html lang="en">
<head>
<style>
    body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h2{
            color:red;
        }
        .logout{
            color:red;

        }
</style>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CheckPass</title>
</head>
<body>
<?php
session_start();
include 'db_connection.php'; // Include the file where OpenCon() function is defined

$con = OpenCon(); // Open the database connection

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$loginMessage = "";
if (!isset($_SESSION['newp'])) {
    $_SESSION['newp'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $enteredUsername = mysqli_real_escape_string($con, $_POST['username']);
    $enteredPassword = mysqli_real_escape_string($con, $_POST['password']);
    $_SESSION["Username"]=$enteredUsername;

    $checkLock = "SELECT looked, login_attempts FROM users WHERE username = '$enteredUsername'";
    $lockResult = mysqli_query($con, $checkLock);
    if ($row = mysqli_fetch_array($lockResult)) {
        if ($row['looked'] == 0) {
            $loginMessage = "Your account has been locked enter your new password that we sent.";
            header('Refresh:1;url=login.php');

        } elseif ($row['login_attempts'] >= 3) {
            $lockAccount = "UPDATE users SET looked = 0 WHERE username = '$enteredUsername'";
            mysqli_query($con, $lockAccount);
            $sql_check_email = "SELECT * FROM users WHERE username='$enteredUsername'";
            $result = $con->query($sql_check_email);
            if ($result->num_rows > 0) {
                $sql = "SELECT email FROM users WHERE username='$enteredUsername' ";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $db_email = $row['email'];
                    function generateRandomPassword($length = 10) {
                        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                        $randomPassword = '';
                        for ($i = 0; $i < $length; $i++) {
                            $randomPassword .= $chars[rand(0, strlen($chars) - 1)];
                        }
                        return $randomPassword;
                    }
                    $random_password = generateRandomPassword();

                    $sql = "UPDATE users SET password='$random_password' WHERE email='$db_email'";
    
                    if ($con->query($sql) === TRUE) {
                        $to = $db_email;
                        $subject = "Password Recovery";
                        $message = "Your new password is: $newPassword <br><br> Click <a href='login.php'>here</a> to login.";
                        $headers = "From: gorg99831@gmail.com\r\n";
                        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                        $retval = mail($to, $subject, $message, $headers);

                        
                        if ($retval == true) {
                            $_SESSION['newp'] = 1;
                            header('Refresh:1;url=login.php');
                            $loginMessage = "Your account has been locked enter your new password that we sent.";

                            $resetAttempts = "UPDATE users SET login_attempts = 0, looked = 1 WHERE Username = '$enteredUsername'";
                            mysqli_query($con, $resetAttempts);
                           

                        } 
                    }
                }
            }
        } else {
            $query = "SELECT * FROM users WHERE username = '$enteredUsername' AND password = '$enteredPassword'";
            $result = mysqli_query($con, $query);
            $query1 = "SELECT users.id FROM users,manager WHERE users.id = manager.manager_id and users.Username='$enteredUsername'";
            $result1 = mysqli_query($con, $query1);

            $query2 = "SELECT users.id FROM users,admin WHERE users.id = admin.id and users.Username='$enteredUsername'";
            $result2 = mysqli_query($con, $query2);
            
            if (mysqli_num_rows($result) > 0) {
                if(mysqli_num_rows($result2) > 0){
                    if ($_SESSION['newp'] == 1) {
                        echo '<script>alert("you must change your password");</script>'; // Display an alert message using JavaScript
                        echo '<meta http-equiv="refresh" content="2;url=changepassword.php">'; // Redirect to changepassword.php after 2 seconds
                        exit;
                    } else {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["Username"] = $enteredUsername;
                        $resetAttempts = "UPDATE users SET login_attempts = 0 WHERE Username = '$enteredUsername'";
                        $currentDateTime = date('Y-m-d H:i:s');
                        mysqli_query($con, $resetAttempts);
                        $dlogin = "UPDATE users SET datetimelogin = '$currentDateTime' WHERE Username = '$enteredUsername'";
                        mysqli_query($con, $dlogin);
                        $updateUserData = "UPDATE usercopy SET login_attempts = login_attempts + 1, entert = entert + 1 WHERE username = '$enteredUsername'";
                        mysqli_query($con, $updateUserData);
                        $_SESSION['id'] = $_POST['username'];
                        echo '<script>alert("you are admin");</script>';
                        echo '<meta http-equiv="refresh" content="2;url=userdata.php">';
                        exit;
                    }
                }
                elseif (mysqli_num_rows($result1) > 0) {
                    if ($_SESSION['newp'] == 1) {
                        echo '<script>alert("you must change your password");</script>'; // Display an alert message using JavaScript
                        echo '<meta http-equiv="refresh" content="2;url=changepassword.php">'; // Redirect to changepassword.php after 2 seconds
                        exit;
                    } else {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["Username"] = $enteredUsername;
                        $resetAttempts = "UPDATE users SET login_attempts = 0 WHERE Username = '$enteredUsername'";
                        $currentDateTime = date('Y-m-d H:i:s');
                        mysqli_query($con, $resetAttempts);
                        $dlogin = "UPDATE users SET datetimelogin = '$currentDateTime' WHERE Username = '$enteredUsername'";
                        mysqli_query($con, $dlogin);
                        $updateUserData = "UPDATE usercopy SET login_attempts = login_attempts + 1, entert = entert + 1 WHERE username = '$enteredUsername'";
                        mysqli_query($con, $updateUserData);
                        $_SESSION['id'] = $_POST['username'];
                        echo '<script>alert("you are traner");</script>';
                        echo '<meta http-equiv="refresh" content="2;url=userdata.php">';
                        exit;
                    }
                } else {
                    if ($_SESSION['newp'] == 1) {
                        echo '<script>alert("you must change your password");</script>'; // Display an alert message using JavaScript
                        echo '<meta http-equiv="refresh" content="2;url=changepassword.php">'; // Redirect to changepassword.php after 2 seconds
                        exit;
                    } else {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["Username"] = $enteredUsername;
                        $resetAttempts = "UPDATE users SET login_attempts = 0 WHERE Username = '$enteredUsername'";
                        $currentDateTime = date('Y-m-d H:i:s');
                        mysqli_query($con, $resetAttempts);
                        $dlogin = "UPDATE users SET datetimelogin = '$currentDateTime' WHERE Username = '$enteredUsername'";
                        mysqli_query($con, $dlogin);
                        $updateUserData = "UPDATE usercopy SET login_attempts = login_attempts + 1, entert = entert + 1 WHERE username = '$enteredUsername'";
                        mysqli_query($con, $updateUserData);
                        $_SESSION['id'] = $_POST['username'];
                        echo '<script>alert("you are a member");</script>';
                        echo '<meta http-equiv="refresh" content="2;url=userdata.php">';
                        exit;
                    }
                }
            }
            
            else {
                $loginMessage = "Invalid username or password.";
                // Increment the login attempts
                $updateAttempts = "UPDATE users SET login_attempts = login_attempts + 1 WHERE username = '$enteredUsername'";
                $updateUserData = "UPDATE usercopy SET login_attempts = login_attempts + 1, failn = failn + 1 WHERE username = '$enteredUsername'";
                mysqli_query($con, $updateUserData);

                header('Refresh:1;url=login.php');
                mysqli_query($con, $updateAttempts);
            }
        }
    } else {
        echo "Invalid username or password.";
    }
    $_SESSION['login_message'] = $loginMessage;
}

CloseCon($con); // Close the database connection
?>
