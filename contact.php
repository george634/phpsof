<?php 
include 'navbar.footer.php'; 
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $date = $_POST["date"];
    $content = $_POST["content"];
    $Lname = $_POST['lname'];
    $email = $_POST['email'];

    $con = OpenCon(); // Open the database connection
    $query = "INSERT INTO contact (firstname, lastname, email, date, content) 
    VALUES ('$name', '$Lname', '$email', '$date', '$content')";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Your content was sent successfully.\\n\\nName: $name\\nDate: $date\\nInserted content: $content');</script>";
    } else {
        echo "<script>alert('Error sending content. Please try again.')</script>";
        // Log detailed error for debugging
        error_log("Error adding user: " . mysqli_error($con));
    }
    
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    height: 100vh;
    color: antiquewhite;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: scroll;
}

body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('contactback.webp') no-repeat center center fixed; 
    background-size: cover;
    filter: blur(1px); /* Adjust the blur radius as needed */
    z-index: -1; /* Ensures it stays behind the content */
}
        form {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background for better contrast */
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px; /* Increased width for better form visibility */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); /* Adds a shadow for depth */
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: antiquewhite;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #000;
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
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: antiquewhite;
        }
        .error-message {
            color: red;
        }
    </style>
    <script>
        function validateEmailAndName() {
            var emailInput = document.getElementById('email');
            var nameInput = document.getElementById('name');
            var emailErrorDiv = document.getElementById('email-error-message');
            var nameErrorDiv = document.getElementById('name-error-message');

            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            // Email validation
            if (!emailPattern.test(emailInput.value)) {
                emailErrorDiv.innerHTML = 'Email must have 1 "@" and a valid domain.';
                return false;
            } else {
                emailErrorDiv.innerHTML = '';
            }

            // Name validation
            if (nameInput.value.length < 5 || nameInput.value.length > 20) {
                nameErrorDiv.innerHTML = 'Name must have between 5 and 20 characters';
                return false;
            } else {
                nameErrorDiv.innerHTML = '';
            }

            return true;
        }
    </script>
</head>
<body>

    <form action="" method="post" onsubmit="return validateEmailAndName()">
    <h1>Contact Us</h1>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter your name" required>
       
        <label for="lname">Last name:</label>
        <input type="text" id="lname" name="lname" placeholder="Enter your last name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="date">Date:</label>
        <input type="datetime-local" id="date" name="date" placeholder="Enter the date" required>

        <label for="content">Content:</label>
        <input type="text" id="content" name="content" placeholder="Enter your interested content" required>

        <div id="email-error-message" class="error-message"></div>
        <div id="name-error-message" class="error-message"></div>
        
        <button type="submit">Submit</button>
    </form>

</body>
</html>
