<?php 
    $title = "Order Confirmation"; 
     
    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);

    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        require '../header.php';
        echo "<p>Invalid request.</p>"; 
        require '../footer.php';
        exit;
    }
    require '../header.php';
    require '../database/database.php';

    if (!isset($_SESSION["sessionId"])) {
    echo "<p>Session expired. Please return to your cart.</p>";
    require '../footer.php';
    exit;
    }

    $sessionId = $_SESSION["sessionId"];

     $name = $_POST['name'];
     $email = $_POST['email'];
     $streetAddress = $_POST['streetAddress'];
     $city = $_POST['city'];
     $state = $_POST['state'];
     $zipcode = $_POST['zipcode'];
     // create random order number for order retrieval 
     $orderNumber = "ORD" . rand(1000, 9999);

     // fetches cart items for later orderItems insertion and safe total price calculation
     $stmt = $pdo->prepare('
        SELECT shoppingCart.productId, shoppingCart.quantity, products.price
        FROM shoppingCart
        JOIN products ON shoppingCart.productId = products.id
        WHERE shoppingCart.sessionId = ?  
     '); 
     $stmt->execute([$sessionId]);
     $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

     // extra check because user can manually navigate to processOrder.php
    if(count($cartItems) === 0) { 
        echo "<p>No items in your cart.</p>"; 
        require '../footer.php';
        exit; 
    }

    $total = 0; 
    foreach ($cartItems as $cartItem) { 
        $subtotal = $cartItem['price'] * $cartItem['quantity'];
        $total += $subtotal;
    }

    $stmt = $pdo->prepare('
        INSERT INTO orders 
        (orderNumber, custEmail, totalPrice, shippingName, streetAddress, city, state, zipcode, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([$orderNumber, $email, $total, $name, $streetAddress, $city, $state, $zipcode, 'Processing']);
    // the orderId is generated after the order is inserted 
    $orderId = $pdo->lastInsertId();
    // insert into orderItems table for each cart item 
    foreach ($cartItems as $cartItem) { 
        $stmt = $pdo->prepare('
            INSERT INTO orderItems 
            (orderId, productId, quantity)
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$orderId, $cartItem['productId'], $cartItem['quantity']]);
        // updates stock after order is completed 
        $stmt = $pdo->prepare('
        UPDATE products 
        SET stock = stock - ?
        WHERE id = ?
        ');
        $stmt->execute([$cartItem['quantity'], $cartItem['productId']]);

    }

    // clear shopping cart for session
    $stmt = $pdo->prepare('
        DELETE FROM shoppingCart
        WHERE sessionId = ?
    ');
    $stmt->execute([$sessionId]);
    ?>

    <h2><strong>Order Confirmation</strong></h2>
        <p>Thank you for your order! Please come again.</p>

        <p> 
            <strong>Order Number:</strong> 
            <?php echo $orderNumber; ?>
        </p>
        <p>
            <strong>Total:</strong> 
            $<?php echo number_format($total, 2); ?>
        </p>
        <p>Please save your order number. You will need it with your email to track your order status. </p>

        <p>
            <a href="../user/orderDetails.php?orderId=<?php echo $orderId; ?>">View Order Details</a>
        </p>
        <p>
            <a href="../products.php">Continue Shopping</a>
        </p>

        <?php require '../footer.php'; ?>




