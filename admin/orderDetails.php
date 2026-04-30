<?php
// to show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['isAdmin'])) {
    header('Location: login.php'); 
    exit; 
}

require '../database/database.php';
$title = "Order Details - Admin View";

if (!isset($_GET['id'])) {
    require '../header.php';
    echo "<p>No order found.</p>";
    require '../footer.php';
    exit;
}

$orderId = $_GET["id"];

// fetch single order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$order) {
    require '../header.php';
    echo "<p>Order not found</p>";
    require '../footer.php';
    exit;
}
$dateTime = new DateTime($order['createdAt']);


// fetch all products in order
$stmt = $pdo->prepare("
    SELECT products.name, products.price, orderItems.quantity
    FROM orderItems
    JOIN products ON orderItems.productId = products.id
    WHERE orderItems.orderId = ?
");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

 if (count($orderItems) === 0) { ?>
    <?php require '../header.php'; ?>
    <p>No items found for this order.</p>
    <p><a href="orders.php">Back to All Orders</a></p>
    <?php require '../footer.php';
    exit; 
    ?>
<?php } ?>

<?php require '../header.php';?>

<h2><strong>Order #</strong><?php echo htmlspecialchars($order['orderNumber']); ?></h2>

<p><strong>Order Date:</strong> <?php echo $dateTime->format("F j, Y g:i A"); ?></p>
<p><strong>Customer Email:</strong> <?php echo htmlspecialchars($order['custEmail']); ?></p>
<p><strong>Notes: </strong><?php echo htmlspecialchars($order['notes'] ?? ''); ?></p>
<form method="POST" action="updateOrder.php">
    <label for="status"><strong>Status:</strong></label>
    <select name="status" id="status">
        <option value="Processing" <?php if($order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
        <option value="Shipping" <?php if($order['status'] == 'Shipping') echo 'selected'; ?>>Shipping</option>
        <option value="Delivered" <?php if($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
    </select>
    <input type="text" name="notes" placeholder="Add notes (optional)">
    <input type="hidden" name="orderId" value="<?php echo htmlspecialchars($orderId); ?>">
    <button type="submit">Update Status</button>
</form>
<br>

<h3>Items Ordered</h3>

    <table border="1"> 
        <tr> 
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>

        <?php 
            $total = 0; 
            foreach ($orderItems as $orderItem) { 
                $subtotal = $orderItem['price'] * $orderItem['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($orderItem['name']); ?></td>
                    <td>$<?php echo number_format($orderItem['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($orderItem['quantity']); ?></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
        <?php } ?>

        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
        </tr>
    </table>

<br>
<p><a href="orders.php">Back to All Orders</a></p>

<?php require '../footer.php'; ?>