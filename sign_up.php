<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        body::before {
    content: "";
    position: absolute;
    top: 100;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('welcome.webp') no-repeat center center fixed; 
    background-size: cover;
    filter: blur(1px); /* Adjust the blur radius as needed */
    z-index: -1; /* Ensures it stays behind the content */
}
        form {
            background-color: #ffffff;
            max-width: 400px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        img.logo1 {
            display: block;
            margin: 0 auto;
            border-radius: 50%;
            height: 150px;
            width: 150px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input.signup {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        input.signup:focus {
            outline: none;
            border-color: #007bff;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body> 
<?php 
include 'navbar.footer.php';
include 'db_connection.php';

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Open database connection
    $con = OpenCon();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data and sanitize
        $username = isset($_POST['username']) ? mysqli_real_escape_string($con, $_POST['username']) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($con, $_POST['password']) : '';
        $email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : '';
        $phone = isset($_POST['phone']) ? mysqli_real_escape_string($con, $_POST['phone']) : '';
        $verifypassword = isset($_POST['verifypassword']) ? mysqli_real_escape_string($con, $_POST['verifypassword']) : '';
        $date_of_birth = isset($_POST['date_of_birth']) ? mysqli_real_escape_string($con, $_POST['date_of_birth']) : '';
        $Fname = isset($_POST['Fname']) ? mysqli_real_escape_string($con, $_POST['Fname']) : '';
        $Lname = isset($_POST['Lname']) ? mysqli_real_escape_string($con, $_POST['Lname']) : '';
        $id = isset($_POST['id']) ? mysqli_real_escape_string($con, $_POST['id']) : '';
    
        // Check if user number is a valid integer and not empty
        if (!empty($id) && is_numeric($id) && intval($id) > 0) {
            // Check if passwords match and meet length requirement
            if ($password !== $verifypassword) {
                echo "<script>alert('Passwords do not match. Please try again.')</script>";
            } elseif (strlen($password) < 8) { // Adjust the minimum length as needed
                echo "<script>alert('Password should be at least 8 characters long.')</script>";
            } else {
                // Check if the username already exists
                $query = "SELECT * FROM users WHERE Username='$username'";
                $result = mysqli_query($con, $query);
    
                if (mysqli_num_rows($result) > 0) {
                    echo "<script>alert('Username already exists. Please choose a different one.')</script>";
                } else {
                    // Insert new user into the database
                    $insert_query = "INSERT INTO users (Username, Password, firstname, lastname, email, phone, birthday, id, looked, login_attempts) 
                                    VALUES ('$username', '$password', '$Fname', '$Lname', '$email', '$phone', '$date_of_birth', '$id', 1, 0)";
                    $insertcopy = "INSERT INTO usercopy (Username, Password, firstname, lastname, email, phone, birthday, id, entert, login_attempts,datetimelogin,failn) 
                    VALUES ('$username', '$password', '$Fname', '$Lname', '$email', '$phone', '$date_of_birth', '$id', 0, 0,0,0)";
                    mysqli_query($con, $insertcopy);
                    if (mysqli_query($con, $insert_query)) {
                        echo "<script>alert('User added successfully.')</script>";
                    } else {
                        echo "<script>alert('Error adding user. Please try again.')</script>";
                        // Log detailed error for debugging
                        error_log("Error adding user: " . mysqli_error($con));
                    }
                }
            }
        } else {
            echo "<script>alert('Please enter a valid user number.')</script>";
        }
    }

    // Close database connection
    CloseCon($con);
?>



    <center>
        <form method="post" action="">
            <img class="logo1" src="logo.jpeg">
            <label for="username">Username:</label>
            <input class="signup" type="text" id="username" name="username" required><br>
            
            <label for="password">Password:</label>
            <input class="signup" type="password" id="password" name="password" required><br>

            <label for="verifypassword">Verify Password:</label>
            <input class="signup" type="password" id="verifypassword" name="verifypassword" required><br>

            <label for="email">Email:</label>
            <input class="signup" type="email" id="email" name="email" required><br>

            <label for="phone">Phone:</label>
            <input class="signup" type="text" id="phone" name="phone" required><br>

            <label for="date_of_birth">Date of Birth:</label>
            <input class="signup" type="date" id="date_of_birth" name="date_of_birth" required><br>

            <label for="Fname">First Name:</label>
            <input class="signup" type="text" id="Fname" name="Fname" required><br>

            <label for="Lname">Last Name:</label>
            <input class="signup" type="text" id="Lname" name="Lname" required><br>

            <label for="id">id:</label>
            <input class="signup" type="text" id="id" name="id" required><br>

            <input class="signup" type="submit" value="Sign Up">
        </form>
    </center>
</br></br>
</br>
</br>
</br>

</body>
</html>