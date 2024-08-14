<?php
include 'db_connection.php';
include 'navbar.footer.php';

$con = OpenCon();

if(isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];

    $userQuery = "SELECT * FROM users WHERE username = '$username'";
    $userResult = mysqli_query($con, $userQuery);
    
    if ($userResult) {
        if (mysqli_num_rows($userResult) > 0) {
            $user = mysqli_fetch_assoc($userResult);
        } else {
            echo "User data not found for username: $username";
        }
    } else {
        echo "Error executing user data query: " . mysqli_error($con);
    }
} else {
    echo "Username session variable not set.";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {

    $newPassword = $_POST['new_password'];

    $updateQuery = "UPDATE users SET password = '$newPassword' WHERE username = '$username'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        $_SESSION['newp'] = 0;
        echo "<script>alert('Password updated successfully');</script>";
        echo "<script>window.location.href = 'login.php';</script>"; // Redirect to login page
        exit; // Stop further execution
        } else {
        echo "<script>alert('Failed to update password');</script>";
    }
}

CloseCon($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #333;
}

input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <div class="container">
        <?php if(isset($user)): ?>
            <h1>Welcome <?php echo isset($user['username']) ? htmlspecialchars($user['username']) : ''; ?></h1>
            <p>First Name: <?php echo isset($user['firstname']) ? htmlspecialchars($user['firstname']) : ''; ?></p>
            <p>Last Name: <?php echo isset($user['lastname']) ? htmlspecialchars($user['lastname']) : ''; ?></p>
            <p>Email: <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?></p>
            <p>Phone: <?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?></p>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <input type="submit" name="update_password" value="Change Password">
            </form>
        <?php else: ?>
            <p>User data not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
