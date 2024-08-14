<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <script type="text/javascript">
        // Redirect to login page and close current window
        window.onload = function() {
            window.location.href = 'login.php';
            window.close();
        };
    </script>
</head>
<body>
    <!-- You can display a message to the user if needed -->
    <p>Logging out...</p>
</body>
</html>
