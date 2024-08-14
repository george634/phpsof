<?php
include 'db_connection.php';

// Open database connection
$con = OpenCon();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form was submitted
    if (isset($_POST['remove_product'])) {
        // Sanitize the product ID to prevent SQL injection
        $product_id = mysqli_real_escape_string($con, $_POST['remove_product']);
        
        // SQL query to delete the product from the database
        $delete_query = "DELETE FROM products WHERE id = '$product_id'";
        
        // Execute the query
        if (mysqli_query($con, $delete_query)) {
            echo "<script>alert('product removed successfully.');</script>";
            echo "<script>window.location = 'userdata.php';</script>";
            exit(); 
                } else {
            echo "Error removing product: " . mysqli_error($con);
        }
    }
}

// Close database connection
CloseCon($con);
?>
