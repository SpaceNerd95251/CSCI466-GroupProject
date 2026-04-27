<?php
    session_start();

    if(!isset($_SESSION['sessionId'])) { 
        $_SESSION['sessionId'] = session_id(); 
    }
?>

<!DOCTYPE html>
<html> 
    <head> 
        <title>CSCI 466 Group Project</title>
    </head>
    <body> 

        <h1> The Store </h1> // this may be changed depending on what we name the store

        <hr> 
            <a href="main.php">Products</a> |
            <a href="cart.php">Cart</a> |
            <a href="orders.php">Orders</a> |

            <?php if (!empty($_SESSION['isAdmin'])): ?>
                <a href="admin/orders.php">Admin</a> |
                <a href="admin/fulfillment.php">Fulfillment</a> |
                <a href="admin.logout.php">Logout</a> 
            <?php else: ?>
                <a href="admin/login.php">Admin Login</a> 
            <?php endif; ?>
        </hr>
    </body>