<?php 

    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);

    $title = "Order Details"; 

    require '../header.php'; 
    require '../database/database.php'; 

    if(!isset($_GET['orderId'])) { 
        echo "<p> No order found.</p>"; 
        require '../footer.php';
        exit; 
    }

    $orderId = $_GET["orderId"];

    // get order details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?"); 
    $stmt->execute([$orderId]); 
    $order = $stmt->fetch(PDO::FETCH_ASSOC); 

    //check if order exist 
    if (!$order) { 
        echo "<p>Order not found</p>";
        require '../footer.php';
        exit; 
    }
    // get all products in the order
    $stmt = $pdo->prepare("
        SELECT products.name, products.price, orderItems.quantity
        FROM orderItems 
        JOIN products ON orderItems.productId = products.id
        WHERE orderItems.orderId = ?
    ");
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // time formatting
    $dateTime = new DateTime($order['createdAt']);
?>

<h2><strong>Order #</strong><?php echo $order['orderNumber']; ?></h2>
    
    <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
    <p><strong>Order Date:</strong> <?php echo $dateTime->format("F j, Y g:i A"); ?></p>
 
    <h3>Items Ordered</h3>
    <!-- check if order contained items -->
    <?php if (count($orderItems) === 0) { ?>
        <p>No items in this order.</p>
    <?php } else { ?>
        <table> 
            <tr> 
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>

            <?php 
                 $total = 0; 
                 // loops through all order items puts them in tables 
                 // calculates total 
                 foreach ($orderItems as $orderItem) { 
                    $subtotal = $orderItem['price'] * $orderItem['quantity'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo $orderItem['name']; ?></td>
                        <td>$<?php echo number_format($orderItem['price'], 2); ?></td>
                        <td><?php echo $orderItem['quantity']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
            <?php } ?>

                 <tr>
                    <!-- total takes up 3 columns -->
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                 </tr>
        </table>
            <?php } ?>

            <p><a href="orderTracking.php">Back to Order Tracking</a></p>
                    
<?php require '../footer.php'; ?>