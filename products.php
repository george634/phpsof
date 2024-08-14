<?php
include 'navbar.footer.php';
include 'db_connection.php';
$conn = OpenCon(); // Open database connection

$sql = "SELECT * FROM products ORDER BY price DESC LIMIT 10";
$mostexp_sql = "SELECT * FROM products ORDER BY price DESC LIMIT 1";
$mostexp_result = mysqli_query($conn, $mostexp_sql);
$mostexp = mysqli_fetch_assoc($mostexp_result);
$result = mysqli_query($conn, $sql);



?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products</title>
<link rel="stylesheet" href="style.css">
<style>
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    
    justify-content: center;
    align-items: center;
    
    overflow: scroll;
}

body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('productsback.webp') no-repeat center center fixed; 
    background-size: 100%; /* Zoom out effect by making the background larger */
    filter: blur(1px); /* No blur */
    z-index: -1; /* Ensures it stays behind the content */
}
.product-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    
}

.product {
    width: 300px;
    margin: 20px;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    position: relative; 
   
    
}

.product img {
    width: 200px;
    height: 150px;
    border-radius: 5px;
}

.product-details {
    margin-top: 10px;
}

.product-details p {
    margin: 5px 0;
}


.buy-button {
    position: absolute; /* Position the button absolutely */
    bottom: 0px; /* Adjust bottom position */
    right:-10px; /* Adjust right position */
    padding:7px; /* Padding for button */
    border: 2px solid #007bff; /* Border style */
    border-radius: 5px; /* Rounded corners */
    background-color: #007bff; /* Button background color */
    color: #fff; /* Button text color */
    font-size: 16px; /* Button text size */
    font-weight: bold; /* Bold font weight */
    text-transform: uppercase; /* Uppercase text */
    cursor: pointer; /* Cursor style */
    transition: background-color 0.3s, color 0.3s; /* Smooth transition effects */
}

.buy-button:hover {
    background-color: #0056b3; /* Darker background color on hover */
}
.product .buy-form {
    position: relative;
    bottom: 10px;
    right: 10px;
}

.inventory-green {
    color: green;
}

.inventory-red {
    color: red;
}
.product .quantity-input {
    margin-bottom: 5px;

    padding: 3px;
    border: 1px solid #ccc;
    border-radius: 60px;
    position: relative;

}

.product .product-id {
    display: none; /* Hide the product ID input visually */
}




</style>
</head>
<body>
<br><br>

<div class="product-container">
    <?php while($row = mysqli_fetch_array($result)): ?>
       
   
         
   
        <div class="product">
            <img src="<?php echo $row['img']; ?>" alt="">
            <p><strong>ID:</strong> <?php echo $row['id']; ?></p>
            <p><strong>Name:</strong> <?php echo $row['pname']; ?></p>
            <p><strong>Price:</strong> <?php echo $row['price']; ?></p>
            <p><strong>Color:</strong> <?php echo $row['color']; ?></p>
            <p><strong>Weight:</strong> <?php echo $row['weight']; ?></p>
            
            
            <?php 
            if ($row['inventory'] <= 3) {
                echo '<p class="inventory-red"><strong>Inventory:</strong> ' . $row['inventory'] . '</p>';
            } else {
                echo '<p class="inventory-green"><strong>Inventory:</strong> ' . $row['inventory'] . '</p>';
            }
            ?>
             <form action="shoppingcartp.php" method="post" class="buy-form">
                <input type="number" name="quantity" value="1" class="quantity-input">
                <input type="hidden" name="productId" class="product-id" value="<?php echo $row['id']; ?>">
                <input type="submit" value="Add To Cart" class="buy-button">
            </form>
        </div>
        <script>
            function validateQuantity(form) {
                var quantityInput = form.querySelector('.quantity-input');
                var productIdInput = form.querySelector('.product-id');
                var maxQuantity = <?php echo $row['inventory']; ?>; // Maximum available inventory

                if (parseInt(quantityInput.value) > maxQuantity) {
                    alert('Quantity cannot exceed available inventory (' + maxQuantity + ').');
                    return false; // Prevent form submission
                }

                return true; // Allow form submission
            }
        </script>
    
   

    <?php endwhile; ?>
</div>

<?php CloseCon($conn); // Close database connection ?>
<br><br><br><br><br><br>

</body>
</html>
