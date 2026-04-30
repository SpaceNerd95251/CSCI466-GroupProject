<?php
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if(!isset($_SESSION['sessionId'])) { 
        $_SESSION['sessionId'] = session_id(); 
    }
    define('BASE_URL', '/~z2071748/CSCI466-GroupProject');
?>

<!DOCTYPE html>
<html> 
    <head> 
        <!-- shows a different title depending on the page, set in other page files -->
        <title><?php echo $title; ?></title>
    </head>
    <body> 

        <h1>CSCI 466 Book Depot</h1> <!-- this may be changed depending on what we name the store -->

        <hr> 

        <nav>
            <!-- make sure relative paths are correct -->
            <a href="<?php echo BASE_URL; ?>/products.php">Products</a> |

            <?php if (!empty($_SESSION['isAdmin'])) { ?>
                <a href="<?php echo BASE_URL; ?>/admin/orders.php">Orders</a> |
                <a href="<?php echo BASE_URL; ?>/admin/logout.php">Logout</a> 
            <?php } else { ?>
                <a href="<?php echo BASE_URL; ?>/cart/cart.php">Cart</a> |
                <a href="<?php echo BASE_URL; ?>/user/orderTracking.php">Track Order</a> |
                <a href="<?php echo BASE_URL; ?>/admin/orders.php">Admin Login</a> 

            <?php } ?>
        </nav>
        <hr>
    