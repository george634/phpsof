<?php
include 'navbar.footer.php';
include 'db_connection.php';

// Start session

// Redirect user to login page if not logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

// Include database connection
$conn = OpenCon(); // Open database connection


$username = $_SESSION['Username'];

// Check if product removal request is sent
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['removeProductId'])) {
    $removeProductId = $_POST['removeProductId'];
    
    // Initialize removed quantity variable
    $removedQuantity = 0;

    // Fetch the quantity of the specified product from the shopping cart
    $fetchQuantitySql = "SELECT quantity FROM shoppingcart WHERE username = '$username' AND productid = $removeProductId LIMIT 1";
    $quantityResult = mysqli_query($conn, $fetchQuantitySql);
    $quantityRow = mysqli_fetch_assoc($quantityResult);

    // Check if quantity row exists
    if ($quantityRow) {
        $removedQuantity = $quantityRow['quantity'];

        // Update shopping cart to remove all quantity of the specified product
        $removeSql = "DELETE FROM shoppingcart WHERE username = '$username' AND productid = $removeProductId";
        mysqli_query($conn, $removeSql);

        // Increase the inventory of the removed product by the removed quantity
        //$increaseInventorySql = "UPDATE products SET inventory = inventory + $removedQuantity WHERE id = $removeProductId";
        //mysqli_query($conn, $increaseInventorySql);
    }
}

// Query to fetch products from shopping cart for the logged-in user
$sql = "SELECT sc.productid, SUM(sc.quantity) AS total_quantity, p.pname, p.price, p.color, p.weight, p.inventory, p.img
        FROM shoppingcart sc
        INNER JOIN products p ON sc.productid = p.id
        WHERE sc.username = '$username' AND sc.checked = 0
        GROUP BY sc.productid";


$result = mysqli_query($conn, $sql);

// Check if any products found in the shopping cart
$totalPrice = 0; // Initialize total price variable
$ch = "SELECT * FROM shoppingcart WHERE username='$username' AND checked=0";
$res=mysqli_query($conn, $ch);
if (mysqli_num_rows($result) > 0 && mysqli_num_rows($res) > 0) {
    // Loop through fetched data and display each product
    while ($row = mysqli_fetch_assoc($result)) {
        $totalPrice += $row['price'] * $row['total_quantity']; // Calculate total price
        echo "<div class='product'>";
        echo "<img src='{$row['img']}' alt='{$row['pname']}'>";
        echo "<div class='product-details'>";
        echo "<p><strong>Name:</strong> {$row['pname']}</p>";
        echo "<p><strong>Price:</strong> {$row['price']}</p>";
        echo "<p><strong>Color:</strong> {$row['color']}</p>";
        echo "<p><strong>Weight:</strong> {$row['weight']}</p>";
        echo "<p><strong>Inventory:</strong> {$row['inventory']}</p>";
        echo "<p><strong>Total Quantity:</strong> {$row['total_quantity']}</p>";
        // Add remove button with form to handle removal
        echo "<form method='post'>";
        echo "<input type='hidden' name='removeProductId' value='{$row['productid']}'>";
        echo "<input type='submit' value='Remove'>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<script>alert('Your shopping cart is empty.'); window.location.href = 'products.php';</script>";
    exit; 
}


// Display total price
echo "<div class='total-price'>Total Price: $totalPrice</div>";
echo "<div style='text-align:center;'>";
echo "<form action='payment.php' method='post'>";
echo "<input type='radio' name='shipping_option' value='Shipping'> Shipping";
echo "<input type='radio' name='shipping_option' value='Pickup'> Pickup";
echo "<br>";

echo "<input type='submit' name='pay_now' value='Pay Now'>";


echo "</form>";
echo "</div>";
echo "<br>";echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

CloseCon($conn);
?>


<style>
    .product-details form {
    margin-top: 10px;
}

.product-details form input[type="submit"] {
    background-color: #ff6347; /* Red color for remove button */
    color: #fff; /* White text color */
    padding: 5px 10px; /* Padding around the button text */
    border: none; /* Remove border */
    border-radius: 3px; /* Rounded corners */
    cursor: pointer; /* Cursor style */
    transition: background-color 0.3s; /* Smooth transition for background color */
}

.product-details form input[type="submit"]:hover {
    background-color: #d6341d; /* Darker red color on hover */
}
.product {
    width: 200px;
    margin-left: 43%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    text-align:center;
    display: flex;
    flex-wrap: wrap;
    justify-content: center; /* Center the products horizontally */
}

.product img {
    width: 100%;
    height: auto;
    border-radius: 5px;
}

.product-details {
    padding: 10px;
}

.product-details p {
    margin: 5px 0;
}

.product-details p strong {
    font-weight: bold;
}

.product-details p:last-child {
    margin-bottom: 0;
}

.total-price {
    font-weight: bold;
    font-size: 20px;
    margin-top: 20px;
    text-align: center;
}

    </style>



