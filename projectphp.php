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
        </style>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckPass</title>
    </head>
<body>

<?php
session_start();
$users = array(
    "0" => array(
        "id" => "123456789",
        "username" => "george",
        "email" => "john.doe@example.com",
        "password" => "12321"
    ),
    "1" => array(
        "id" => "987654321",
        "username" => "jane",
        "email" => "jane.smith@example.com",
        "password" =>"12345"
    ),
    "2" => array(
        "id" => "987654321",
        "username" => "jg",
        "email" => "jane.smith@example.com",
        "password" =>"555"
    ),
    "3" => array(
        "id" => "987654321",
        "username" => "rabe3",
        "email" => "jane.smith@example.com",
        "password" =>"666"
    ),
    "4" => array(
        "id" => "987654321",
        "username" => "uuu",
        "email" => "jane.smith@example.com",
        "password" =>"9999"
    ),
    "5" => array(
        "id" => "987654321",
        "username" => "jjjj",
        "email" => "jane.smith@example.com",
        "password" =>"7777"
    ),
    "6" => array(
        "id" => "987654321",
        "username" => "iiii",
        "email" => "jane.smith@example.com",
        "password" =>"8888"
    ),
    "7" => array(
        "id" => "987654321",
        "username" => "kkk",
        "email" => "jane.smith@example.com",
        "password" =>"6666"
    ),
    "8" => array(
        "id" => "987654321",
        "username" => "ooo",
        "email" => "jane.smith@example.com",
        "password" =>"55555"
    ),
    "9" => array(
        "id" => "987654321",
        "username" => "ttt",
        "email" => "jane.smith@example.com",
        "password" =>"4444"
    ),

);
// $_SESSION['users'] = $users;





    $username = $_POST['username'];
    $password = $_POST['password'];

   
    $usernameFound = false;

    for ($i = 0; $i < 10; $i++) {
        if ($username ==$users[$i]['username']) {
            $usernameFound=true;
            if ($password==$users[$i]['password']) {
                echo " welcommmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm";
                header("Refresh:1; url=homepage.php");
            //    exit();
            } else {
                $_SESSION[$username]= isset($_SESSION[$username])?  $_SESSION[$username]+1:1;
                echo "Invalid password";

                if ($_SESSION[$username] >= 3) {
                         $_SESSION[$username] = 0;
                        echo "Invalid username or password. Too many attempts.";
                } 
            }
        } 
        if($usernameFound==false) {
            echo "<h2>Invalid username\n$username</h2>";
            header("Refresh:1; url=login.php");
            echo "<script type='text/javascript'>alert('$username');</script>";

            break;
        }

        
        

    }

   


   
    

?>
</body>
</html>