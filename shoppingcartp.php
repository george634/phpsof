<?php

include 'db_connection.php';
$conn = OpenCon(); // Open database connection
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["Username"]) ) {
    $id = $_POST['productId'];
    $_SESSION["id"]=$id;
    $quantity=$_POST['quantity'];
        $username = $_SESSION["Username"];


    echo $id;
    echo $quantity;
    $query = "SELECT inventory FROM products WHERE id = $id";
    $inventory_result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($inventory_result);
    $availableInventory = $row['inventory'];

    if ($quantity <= 0 || $quantity > $availableInventory) {
        // Invalid quantity, show error message and redirect back
        echo "<script>alert('you cant buy more than what we have in the inventory\\n max inventory:$availableInventory'); window.location.href = 'products.php';</script>";
        exit(); // Terminate script to prevent further execution
    } else {
        $sql_insert = "INSERT INTO shoppingcart (username,productid, quantity) VALUES ('$username','$id', '$quantity')";
        if (mysqli_query($conn, $sql_insert)) {
            //$updatedInventory = $availableInventory - $quantity;
           // $update_query = "UPDATE products SET inventory = $updatedInventory WHERE id = $id";
            //mysqli_query($conn, $update_query);
            echo "<script>alert('Your product has been successfully added.');</script>";
            echo "<script>window.location.href = 'products.php';</script>";  
              }
       
    }
}


CloseCon($conn); 

?>

