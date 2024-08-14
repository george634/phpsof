<?php
include 'navbar.footer.php';
include 'db_connection.php';

// Check if the 'email' key is set in the $_POST array
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $conn = OpenCon();

    $sql_check_email = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql_check_email);
    if ($result->num_rows > 0) {
        function generateRandomPassword($length = 10) {
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $randomPassword = '';
            for ($i = 0; $i < $length; $i++) {
                $randomPassword .= $chars[rand(0, strlen($chars) - 1)];
            }
            return $randomPassword;
        }

    // Procedure for password recovery
    $random_password = generateRandomPassword(8);

    $sql = "UPDATE users SET password='$random_password' WHERE email='$email'";
    
    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // Send email with the new password
        $to = $email;
        $subject = "Password Recovery";
        $message = "Your new password is: $random_password";
        $headers = "From: gorg99831@gmail.com";
        $retval = mail($to, $subject, $message, $headers);
        
        if ($retval == true) {
            $_SESSION['newp'] = 1;
            echo "<script>alert('Message sent successfully.')</script>";
            header('Refresh:4;url=login.php');

        } else {
            echo "<script>alert('Message could not be sent.')</script>";
            header('Refresh:1;url=forgetpassword.php');

        }
    } else {
        echo "Error updating record: " . $conn->error;
    }

}
else {
    echo "<script>alert('User with this email does not exist.')</script>";

}
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: black;
            color: blanchedalmond;
        }

        form {
            background-color: black;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: greenyellow;
        }
    </style>
</head>
<body>
    <div>
        <form method="post">
            <label for="email">Enter your email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
