<?php
session_start();
include 'db_connection.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

// Include database connection
$conn = OpenCon(); // Open database connection

// Retrieve user's username from session
$username = $_SESSION['Username'];

// Check if pay now button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_now'])) {
    // Retrieve selected shipping option
    $shippingOption = $_POST['shipping_option'];
    
    // Retrieve product IDs and quantities from the shopping cart
    $productQuantities = [];
    $fetchCartItemsSql = "SELECT productid, quantity FROM shoppingcart WHERE username = '$username' AND checked = 0";
    $cartItemsResult = mysqli_query($conn, $fetchCartItemsSql);
    while ($row = mysqli_fetch_assoc($cartItemsResult)) {
        $productQuantities[$row['productid']] = $row['quantity'];
    }
    
    // Calculate the total price
    $totalPrice = 0;
    foreach ($productQuantities as $productId => $quantity) {
        $fetchProductPriceSql = "SELECT price FROM products WHERE id = $productId";
        $productPriceResult = mysqli_query($conn, $fetchProductPriceSql);
        $productPrice = mysqli_fetch_assoc($productPriceResult)['price'];
        $totalPrice += $productPrice * $quantity;
    }
    
    // Add shipping cost if shipping option is selected
    if ($shippingOption === 'Shipping') {
        $totalPrice += 50;
    }
    
    // Update inventory in the products table
    foreach ($productQuantities as $productId => $quantity) {
        $updateInventorySql = "UPDATE products SET inventory = inventory - $quantity WHERE id = $productId";
        mysqli_query($conn, $updateInventorySql);
    }
    
    // Update database to mark the corresponding rows as checked
    $productIds = array_keys($productQuantities);
    $productIdsStr = implode(',', $productIds);
    if (!empty($productIdsStr)) {
        $updateCheckedSql = "UPDATE shoppingcart SET checked = 1 WHERE username = '$username' AND productid IN ($productIdsStr)";
        mysqli_query($conn, $updateCheckedSql);
    }
    
    // Insert the order into the database
    $insertOrderSql = "INSERT INTO orders (username, typeofshipping, totaleprice) VALUES ('$username', '$shippingOption', $totalPrice)";
    mysqli_query($conn, $insertOrderSql);
    
    // Retrieve order details for display
    $orderId = mysqli_insert_id($conn); // Get the ID of the last inserted order
    $orderDetailsSql = "SELECT * FROM orders WHERE id = '$orderId' AND username = '$username'";
    $orderDetailsResult = mysqli_query($conn, $orderDetailsSql);
    $orderDetails = mysqli_fetch_assoc($orderDetailsResult);
    
    // Display order details
    if ($orderDetails) {
        echo "<h2>Your Order Details:</h2>";
        echo "<p>Order ID: " . $orderDetails['id'] . "</p>";
        echo "<p>Username: " . $orderDetails['username'] . "</p>";
        echo "<p>Shipping Option: " . $orderDetails['typeofshipping'] . "</p>";
        echo "<p>Total Price: $" . $orderDetails['totaleprice'] . "</p>";
        echo "<button onclick=\"redirectToProducts()\">Go to Products</button>";

    } else {
        echo "<p>Failed to retrieve order details.</p>";
    }
}

CloseCon($conn);
?>
<script>
    function redirectToProducts() {
        window.location.href = 'products.php';
    }
</script>
