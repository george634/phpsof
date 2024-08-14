<?php include 'navbar.footer.php'; 
include 'db_connection.php';
if(isset($_SESSION['login_message'])) {
    $loginMessage = $_SESSION['login_message'];
    unset($_SESSION['login_message']);
} else {
    $loginMessage = ''; // Set default message if not set
}
?>
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
            background-color:black;
        }
       
body::before {
    content: "";
    position: absolute;
    top: 100;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('loginback.webp') no-repeat center center fixed; 
    background-size: cover;
    filter: blur(1px); /* Adjust the blur radius as needed */
    z-index: -1; /* Ensures it stays behind the content */
}
        form {
            background-color: black;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color:blanchedalmond;

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

       
        .log:hover {
            background-color: greenyellow;

        }
        p{
           color:#fff; 
           background-Color:black;
           padding: 3px;
        }
        a{

            font-size:20px;
        }
        div {
    background-color: black;
    padding: 9px;
    border-radius: 8%;
    
}
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>


    
</head>
<body>






<div>
<form method="post" action="checklogin.php">
        <label for="username">username :</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required><br>
        
        <label for="password">pasword:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required><br>

        <input type="submit" value="Log In">
        <h4><?php echo $loginMessage; ?></h4>
    </form>
    <p>you dont have an account yet? <a href="sign_up.php">sign up</a></p>
    <p>Forgot password?<a href="forgetpassword.php">restart password</a> </p>
    </div>
</body>
</html>
