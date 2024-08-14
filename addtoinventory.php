<?php
include 'db_connection.php';
$con = OpenCon();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $x = $_POST['product_id'];
    $update = "SELECT pname FROM products WHERE id='$x'";
    $result = mysqli_query($con, $update);

    // Check if the query was successful
    if ($result) {
        // Retrieve the product name from the query result
        $row = mysqli_fetch_assoc($result);
        $pname = $row['pname'];
        
        // Update inventory for each product
        foreach ($_POST['product_id'] as $key => $product_id) {
            $quantity = $_POST['quantity'][$key];
            $update_query = "UPDATE products SET inventory = inventory + $quantity WHERE id = $product_id";
            mysqli_query($con, $update_query);
        }
        
        // Display alert and redirect
        echo "<script>alert('The inventory of $pname has been updated'); window.location.href = 'userdata.php';</script>";
        exit(); // Make sure to exit after redirection to prevent further execution
    } else {
        // Handle database error if the query fails
        echo "Error: " . mysqli_error($con);
    }
} 
?>
