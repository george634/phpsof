<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Gym</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
        }

        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: black;
            padding: 23px;
            z-index: 1000;
        }

        footer {
            background-color: black;
            color: white;
            text-align: center;
            padding: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
            display: none; /* Initially hide the footer */
        }

        a {
            background-color: black;
            color: antiquewhite;
            text-decoration: none;
            font-size: 22px;
            padding: 20px;
        }

        .logo {
            position: fixed;
            top: 12px;
            right: 20px;
            z-index: 1001;
            width: 80px;
            height: 50px;
        }

        .gym-name {
            font-size: 20px;
            font-weight: bold;
            color: antiquewhite;
            text-align: center;
            margin-top: -32px;
            margin-bottom:22px;
            z-index: 1001;
            position: fixed;
            right: 115px;
            font-family: 'Helvetica Neue', sans-serif;
        }

        .logo2 {
            position: fixed;
            top: 12px;
            right: 250px;
            z-index: 1001;
            width: 80px;
            height:50px;
        }

        .profile-btn {
            position: relative;
            display: inline-block;
        }

        .profile-btn-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            padding: 12px 16px;
            z-index: 1;
        }

        .profile-btn:hover .profile-btn-content {
            display: block;
        }
       
.r {
    width: 50px;
    height: 40px;
    margin-bottom: -20px;
    margin-left:30px;
   

}
button {
            background-color: black;
            color: antiquewhite;
            padding: 10px 15px;
            border-color: antiquewhite;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: blue;
        }
    </style>
</head>
<body>

<nav>
    <a href="homepage.php">Home</a>
    <a href="contact.php">contact us</a>
    <?php
    if ((isset($_SESSION['loggedin']) && $_SESSION['loggedin']) && (isset($_SESSION['newp']) && $_SESSION['newp']==0)) {
        echo '<a href="products.php">Products</a>';
        echo '<div class="profile-btn">';
        echo '<button>Profile</button>';
        echo '<div class="profile-btn-content">';
        echo '<button onclick="location.href=\'userdata.php\'">User Data</button>';
        echo '<button onclick="location.href=\'graph.php\'">graph</button>';
        echo"<br>";

        echo '<button onclick="location.href=\'logout.php\'">Logout</button>';

        echo '</div>';
        echo '</div>';
        echo '<a href="incart.php"><img src="gym.png" class="r"></a>';
    }else {
        echo '<a href="login.php">log in</a>';
    }
    ?>
    <img src="logo2.jpeg" class="logo2" alt="Logo">
    <img src="logo2.jpeg" class="logo" alt="Logo">
    <div class="gym-name">GT FlexZone</div>
</nav>

<footer id="footer">
    <p>&copy; 2024 My Gym. All rights reserved.</p>
</footer>

<script>
    // JavaScript to show footer when scrolled to the end of the page
    window.addEventListener('scroll', function () {
        var footer = document.getElementById('footer');
        var distanceToBottom = document.body.scrollHeight - window.innerHeight - window.scrollY;
        if (distanceToBottom < 10) { // Adjust as needed, this value defines how close to the bottom the user must be for the footer to appear
            footer.style.display = 'block';
        } else {
            footer.style.display = 'none';
        }
    });
</script>

<br> <br> <br> <br> <br>
</body>
</html>
