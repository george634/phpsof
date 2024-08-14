<?php
/*
<html>
    <style>
        h2{
            text-align:center;
            font-family:ARIAL;
            padding:20px;
            color:blanchedalmond;
            
        }
        h1{
            text-align:center;
            color:blanchedalmond;
        }
      body{
        background-color:black;
      }
        <body>
        </style>
<?php
        include 'db_connection.php';

    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $lname = $_POST["lname"];
        $email = $_POST["email"];
        $date = $_POST["phnumber"];
        $content = $_POST["content"];
        $con = OpenCon(); // Open the database connection
        $query="INSERT INTO contact VALUES('$content','$name','$date')"; 
        mysqli_query($con, $query);
        echo"<h1>the form has been sent sucssfuly</h1>";
        echo"<br><br><br><br><br>";
        echo "<h2>Name: $name</h2>";
        echo "<h2>Last name: $lname</h2>";
        echo "<h2>Mail:  $email</h2>";
        echo "<h2>Phone number:$phnumber</h2>";
        echo "<h2>Interted content:  $content</h2>";
        

    } else {
        echo "<p>Form submission error</p>";
    }
?>
</body>

</html>
*/
?>