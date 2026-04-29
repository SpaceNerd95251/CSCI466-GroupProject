<?php
    session_start();

    if(!isset($_SESSION['sessionId'])) { 
        $_SESSION['sessionId'] = session_id(); 
    }
?>

<!DOCTYPE html>
<html> 
    <head> 
        <!-- shows a different title depending on the page, set in other page files -->
        <title><?php echo $title; ?></title>
    </head>
    <body> 

        <h1> The Store </h1> <!-- this may be changed depending on what we name the store -->

        <hr> 

        <nav>
                <!-- make sure relative paths are correct -->
            <a href="products.php">Products</a> |

            <?php if (!empty($_SESSION['isAdmin'])) { ?>
                <a href="admin/orders.php">Orders</a> |
                <a href="admin/logout.php">Logout</a> 
            <?php } else { ?>
                <a href="cart/cart.php">Cart</a> |
                <a href="user/orderTracking.php">Track Order</a> |
                <a href="admin/login.php">Admin Login</a> 

            <?php } ?>
        </nav>
        <hr>
    