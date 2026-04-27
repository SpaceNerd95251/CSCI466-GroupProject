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
            <a href="main.php">Products</a> |
            <a href="cart.php">Cart</a> |
            <a href="orders.php">Orders</a> |

            <?php if (!empty($_SESSION['isAdmin'])) { ?>
                <a href="admin/orders.php">Admin</a> |
                <a href="admin/fulfillment.php">Fulfillment</a> |
                <a href="admin/logout.php">Logout</a> 
            <?php } else { ?>
                <a href="admin/login.php">Admin Login</a> 
            <?php } ?>
        </nav>
        <hr>
    