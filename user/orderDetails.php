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
    // gets all previous orders by email once they have been found to be a customer 
    // excludes the orginal order 
    $stmt = $pdo->prepare("
        SELECT id, orderNumber, status, totalPrice, createdAt
        FROM orders
        WHERE custEmail = ?
        AND id != ?
        ORDER BY createdAt DESC
    ");
    $stmt->execute([$order['custEmail'], $orderId]);
    $previousOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <?php 
        $total = 0; 
    ?>
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
                 // loops through all order items puts them in tables 
                 // calculates total 
                 foreach ($orderItems as $orderItem) { 
                    $subtotal = $orderItem['price'] * $orderItem['quantity'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($orderItem['name']); ?></td>
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

<h3>All Orders for <?php echo htmlspecialchars($order['custEmail']); ?></h3>

    <?php if (count($previousOrders) === 0) { ?>

        <p>No previous orders found for this email.</p>
        <p>
            <strong>Total Spent Across All Orders:</strong>
            $<?php echo number_format($total, 2); ?>
        </p>
    <?php } else { ?>

        <?php 
            $totalSpent = 0;

            foreach ($previousOrders as $previousOrder) {
                $totalSpent += $previousOrder['totalPrice'];
            }
            $totalSpent += $total;
        ?>

        <table>
            <tr>
                <th>Order Number</th>
                <th>Status</th>
                <th>Total</th>
                <th>Order Date</th>
                <th>View</th>
            </tr>

            <?php foreach ($previousOrders as $previousOrder) { 
                $previousDate = new DateTime($previousOrder['createdAt']);
            ?>

                <tr>
                    <td><?php echo htmlspecialchars($previousOrder['orderNumber']); ?></td>
                    <td><?php echo htmlspecialchars($previousOrder['status']); ?></td>
                    <td>$<?php echo number_format($previousOrder['totalPrice'], 2); ?></td>
                    <td><?php echo $previousDate->format("F j, Y g:i A"); ?></td>
                    <td>
                        <a href="orderDetails.php?orderId=<?php echo $previousOrder['id']; ?>">
                            View
                        </a>
                    </td>
                </tr>

            <?php } ?>

        </table>

        <p>
            <strong>Total Spent Across All Orders: </strong>
            $<?php echo number_format($totalSpent, 2); ?>
        </p>

    <?php } ?>


            <p><a href="orderTracking.php">Back to Order Tracking</a></p>
                    
<?php require '../footer.php'; ?>