<?php
include 'db_connection.php';

// Open database connection
$con = OpenCon();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   

    
    $img = $_POST['img'];
    $id = $_POST['id'];
    $pname = $_POST['pname'];
    $price = $_POST['price'];
    $color = $_POST['color'];
    $weight = $_POST['weight'];
    $inventory = $_POST['inventory'];
    
    $insert_query = "INSERT INTO products (img, id, pname, price, color, weight, inventory) 
    VALUES ('$img', '$id', '$pname', '$price', '$color', '$weight', '$inventory')";

if ($con->query($insert_query) === TRUE) {
    echo "<script>alert('product added successfully.');</script>";
    // Redirect back to whatmangementcando page
    echo "<script>window.location = 'userdata.php';</script>";
} else {
    echo "Error: " . $insert_query . "<br>" . $con->error;
}

// Close database connection
CloseCon($con);

   
}
?>
