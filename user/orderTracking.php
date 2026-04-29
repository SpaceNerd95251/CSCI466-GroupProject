<?php 

    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);

    $title = "Order Tracking"; 
    require '../database/database.php';
    // for a correctly formatting error message 
    $error = ""; 

    // checks if the form was submitted, then redirects to orderDetails.php
    if($_SERVER["REQUEST_METHOD"] == 'POST') { 
        $orderNumber = $_POST['orderNumber'];
        $email = $_POST['email'];

        $stmt = $pdo->prepare('
        SELECT id 
        FROM orders
        WHERE orderNumber = ? 
        AND email = ?
        ');
        $stmt->execute([$orderNumber, $email]); 
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if($order) { 
            // goes to orderDetail.php with specified orderID
            header('Location: orderDetails.php?orderId=' . $order['id']);
            exit; 
        }else { 
            $error = "No order found with specified order number and email.";
        }
    }
    require '../header.php';
?>
<h2>Track Your Order</h2>
    <?php if ($error != "") { 
        echo "<p>$error</p>";
    }
    ?>
    <form method="post"> 
        <p>
             Order Number: 
            <input type="text" name="orderNumber" required> 
        </p>
        <p> 
            Email: 
            <input type="text" name="email" required>
        </p>
        <p> 
            <input type="submit" value="Track Order">
        </p>
    </form>

<?php require '../footer.php'; ?>
