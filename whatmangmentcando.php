<?php
include 'navbar.footer.php';
include 'db_connection.php';

$con = OpenCon();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (isset($_POST["submit"])) {
        $action = $_POST["submit"];
        
        switch ($action) {
            case "Add User":
                echo "<center>";
                echo "<form method='post' action='adduser.php'>";
                echo "<h2>Add User</h2>";
                echo "<label for='username'>Username:</label>";
                echo "<input type='text' id='username' name='username' required><br>";
                
                echo "<label for='password'>Password:</label>";
                echo "<input type='password' id='password' name='password' required><br>";
            
                echo "<label for='email'>Email:</label>";
                echo "<input type='email' id='email' name='email' required><br>";
            
                echo "<label for='phone'>Phone:</label>";
                echo "<input type='text' id='phone' name='phone' required><br>";
            
                echo "<label for='date_of_birth'>Date of Birth:</label>";
                echo "<input type='date' id='date_of_birth' name='date_of_birth' required><br>";
            
                echo "<label for='Fname'>First Name:</label>";
                echo "<input type='text' id='Fname' name='Fname' required><br>";
            
                echo "<label for='Lname'>Last Name:</label>";
                echo "<input type='text' id='Lname' name='Lname' required><br>";
            
                echo "<label for='id'>ID:</label>";
                echo "<input type='text' id='id' name='id' required><br>";
            
                echo "<input type='submit' value='Add User' name='submit'>";
                echo "</form>";
                echo "</center>";
                echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
               
            break;
            
            case "Remove User":
                $query = "SELECT * FROM users";
                $result = mysqli_query($con, $query);
                if (mysqli_num_rows($result) > 0) {
                    echo "<table border='1' id='managerData'>
                        <tr>
                            <th>Username</th>
                            <th>Password</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Birthday</th>
                            <th>ID</th>
                            <th>Looked</th>
                            <th>Login Attempts</th>
                            <th>Remove</th>
                        </tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['username']}</td>";
                        echo "<td>{$row['password']}</td>";
                        echo "<td>{$row['firstname']}</td>";
                        echo "<td>{$row['lastname']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['phone']}</td>";
                        echo "<td>{$row['birthday']}</td>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['looked']}</td>";
                        echo "<td>{$row['login_attempts']}</td>";
                        echo "<td>
                                <form method='post' action='removeuser.php'>
                                    <input type='hidden' name='remove_user' value='{$row['username']}'>
                                    <input type='submit' value='Remove'>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
                } else {
                    echo "<p>No users found.</p>";
                }
                break;
                
            case "Add Product":
                echo "<center>";
                echo "<form method='post' action='addproduct.php'>";
                echo "<h2>Add Product</h2>";

                echo "<label for='img'>Image URL:</label>";
                echo "<input type='hidden' id='img' name='src' value='img1.jpeg' required>";

                echo "<label for='id'>ID:</label>";
                echo "<input type='text' id='id' name='id' required><br>";

                echo "<label for='pname'>Product Name:</label>";
                echo "<input type='text' id='pname' name='pname' required><br>";

                echo "<label for='price'>Price:</label>";
                echo "<input type='number' id='price' name='price' required><br>";

                echo "<label for='color'>Color:</label>";
                echo "<input type='text' id='color' name='color' required><br>";

                echo "<label for='weight'>Weight:</label>";
                echo "<input type='number' id='weight' name='weight'  required><br>";

                echo "<label for='inventory'>Inventory:</label>";
                echo "<input type='number' id='inventory' name='inventory' required><br>";
                echo "<br>";echo "<br>";

                echo "<input type='submit' value='Add Product' name='submit'>";
                echo "</form>";
                echo "</center>";

                break;
                
            case "Remove Product":
                $query = "SELECT * FROM products";
                $result = mysqli_query($con, $query);
                if (mysqli_num_rows($result) > 0) {
                    echo "<table border='1' id='managerData'>
                            <tr>
                                <th>Image</th>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Color</th>
                                <th>Weight</th>
                                <th>Inventory</th>
                                <th>Remove</th>
                            </tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td><img src='{$row['img']}' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['pname']}</td>";
                        echo "<td>{$row['price']}</td>";
                        echo "<td>{$row['color']}</td>";
                        echo "<td>{$row['weight']}</td>";
                        echo "<td>{$row['inventory']}</td>";
                        echo "<td>
                                <form method='post' action='removeproduct.php'>
                                    <input type='hidden' name='remove_product' value='{$row['id']}'>
                                    <input type='submit' value='Remove'>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
                } else {
                    echo "<p>No products found.</p>";
                }
                break;
                
                case "Show Additional Options":
                    $query = "SELECT * FROM usercopy";
                    $result = mysqli_query($con, $query);
                    
                    if (mysqli_num_rows($result) > 0) {
                        echo "<table border='1'>";
                        echo "<tr>
                                <th>Username</th>
                                <th>Password</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Birthday</th>
                                <th>ID</th>
                                <th>Entert</th>
                                <th>Login Attempts</th>
                                <th>Date Time Login</th>
                                <th>Failn</th>
                              </tr>";
                
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['username'] . "</td>";
                            echo "<td>" . $row['password'] . "</td>";
                            echo "<td>" . $row['firstname'] . "</td>";
                            echo "<td>" . $row['lastname'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['phone'] . "</td>";
                            echo "<td>" . $row['birthday'] . "</td>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['entert'] . "</td>";
                            echo "<td>" . $row['login_attempts'] . "</td>";
                            echo "<td>" . $row['datetimelogin'] . "</td>";
                            echo "<td>" . $row['failn'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No records found in usercopy table.";
                    }
                    break;
                    case "add to inventory":
                        // Retrieve products from the database
                        $query = "SELECT * FROM products";
                        $result = mysqli_query($con, $query);
                    
                        if (mysqli_num_rows($result) > 0) {
                            // Display products in a table within a form
                            echo "<form method='post' action='addtoinventory.php' class='f1'>";
                            echo "<table class='product-table'>";
                            echo "<tr>
                                    <th>Product Image</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Color</th>
                                    <th>Weight</th>
                                    <th>Inventory</th>
                                    <th>Add Quantity</th>
                                    <th>Action</th>
                                  </tr>";
                    
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><img src='{$row['img']}' alt='Product Image'></td>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td>{$row['pname']}</td>";
                                echo "<td>{$row['price']}</td>";
                                echo "<td>{$row['color']}</td>";
                                echo "<td>{$row['weight']}</td>";
                                echo "<td>{$row['inventory']}</td>";
                                echo "<td><input type='number' name='quantity[]' value='0' class='quantity-input'></td>";
                                echo "<td><input type='hidden' name='product_id[]' value='" . $row['id'] . "'></td>";
                                echo "<td><button type='submit' name='submit[]' class='add-button'>Add to Inventory</button></td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                            echo "</form>";
                        } else {
                            echo "No products found in the database.";
                        }
                        echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
                    
                        break;
                    
                        case "Show all product":

                            $query = "SELECT * FROM products";
                        $result = mysqli_query($con, $query);
                        if (mysqli_num_rows($result) > 0) {
                            // Display products in a table within a form
                            echo "<table class='product-table'>";
                            echo "<tr>
                                    <th>Product Image</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Color</th>
                                    <th>Weight</th>
                                    <th>Inventory</th>
                                    
                                  </tr>";
                    
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><img src='{$row['img']}' alt='Product Image'></td>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td>{$row['pname']}</td>";
                                echo "<td>{$row['price']}</td>";
                                echo "<td>{$row['color']}</td>";
                                echo "<td>{$row['weight']}</td>";
                                echo "<td>{$row['inventory']}</td>";
                               
                                echo "</tr>";
                            }
                            echo "</table>";
                            echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";

                        }
                            break;
                            case "Show all users":
                                $query = "SELECT * FROM users";
                                $result = mysqli_query($con, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<table border='1' id='managerData'>
                                        <tr>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Birthday</th>
                                            <th>ID</th>
                                            <th>Looked</th>
                                            <th>Login Attempts</th>
                                        </tr>";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        
                                        echo "<td>{$row['username']}</td>";
                                        echo "<td>{$row['password']}</td>";
                                        echo "<td>{$row['firstname']}</td>";
                                        echo "<td>{$row['lastname']}</td>";
                                        echo "<td>{$row['email']}</td>";
                                        echo "<td>{$row['phone']}</td>";
                                        echo "<td>{$row['birthday']}</td>";
                                        echo "<td>{$row['id']}</td>";
                                        echo "<td>{$row['looked']}</td>";
                                        echo "<td>{$row['login_attempts']}</td>";
                                       
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                    echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
                                }
                                break;
                                case "Show Order":
                                    $query = "SELECT o.username AS order_username, s.username AS cart_username, o.totaleprice, o.typeofshipping
                                              FROM orders o
                                              INNER JOIN shoppingcart s ON o.username = s.username
                                              WHERE s.checked = 1";
                                
                                    $result = mysqli_query($con, $query);
                                
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $order_username = $row['order_username'];
                                            $cart_username = $row['cart_username'];
                                            $totaleprice = $row['totaleprice'];
                                            $typeoffshipping = $row['typeofshipping'];
                                
                                            $res_query = "SELECT p.*
                                                          FROM products p
                                                          INNER JOIN shoppingcart s ON p.id = s.productid
                                                          WHERE s.checked = 1 AND s.username = '$order_username'";
                                            $res_result = mysqli_query($con, $res_query);
                                
                                            if ($res_result && mysqli_num_rows($res_result) > 0) {
                                                echo "<h2>Order Details for Cart Username: $cart_username</h2>";
                                                echo "<table border='1'>";
                                                echo "<tr>
                                                        <th>image</th>
                                                        <th>Cart Username</th>
                                                        <th>Total Price</th>
                                                        <th>Type of Shipping</th>
                                                        <th>Product ID</th>
                                                        <th>Product Name</th>
                                                        <th>Price</th>
                                                        <th>Color</th>
                                                        <th>Weight</th>
                                                        <th>Inventory</th>
                                                      </tr>";
                                
                                                while ($res_row = mysqli_fetch_assoc($res_result)) {
                                                    echo "<tr>";
                                                    echo "<td><img src='{$res_row['img']}' alt='{$res_row['pname']}'></td>"; // Display image

                                                    echo "<td>$cart_username</td>";
                                                    echo "<td>$totaleprice</td>";
                                                    echo "<td>$typeoffshipping</td>";
                                                    echo "<td>{$res_row['id']}</td>";
                                                    echo "<td>{$res_row['pname']}</td>";
                                                    echo "<td>{$res_row['price']}</td>";
                                                    echo "<td>{$res_row['color']}</td>";
                                                    echo "<td>{$res_row['weight']}</td>";
                                                    echo "<td>{$res_row['inventory']}</td>";
                                                    echo "</tr>";
                                                }
                                                echo "</table>";
                                            } else {
                                                echo "<p>No products found for Cart Username: $cart_username</p>";
                                            }
                                        }
                                    } else {
                                        echo "<script>alert('you dont have any orders.');";
                                        echo "window.location.href = 'userdata.php';</script>";
                                    }                                    
                                    break;
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                    
                    
                    
                    
                    
                    
                    
                    
        }
    }
}
?>
 <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Form Styles */
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"] {
            width: calc(100% - 12px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }
        /* Table Styles */
.product-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.product-table th,
.product-table td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
}

.product-table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.product-table img {
    max-width: 100px;
    max-height: 100px;
}

.product-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.product-table tr:hover {
    background-color: #f2f2f2;
}
.f1{
    width: 1000px;
}

    </style>