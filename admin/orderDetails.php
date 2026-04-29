<?php
// to show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$StatusMessage = "";

// admin login
$correctPassword = "password123";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_pass'])) {
    if ($_POST['admin_pass'] === $correctPassword) {
        $_SESSION['isAdmin'] = true;
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        $StatusMessage = "Incorrect password.";
    }
}

if (empty($_SESSION['isAdmin'])) {
    $title = "Admin Login";
    require '../header.php';
    ?>
    <h2>Admin Access Required</h2>
    <?php if ($StatusMessage) echo "<p style='color:red;'>$StatusMessage</p>"; ?>
    <form method="POST" action="">
        <label for="admin_pass">Password:</label>
        <input type="password" name="admin_pass" id="admin_pass" required>
        <button type="submit">Login</button>
    </form>
    <?php
    require '../footer.php';
    exit; 
}

require '../database/database.php';
$title = "Order Details - Admin View";

if (!isset($_GET['orderId'])) {
    require '../header.php';
    echo "<p>No order found.</p>";
    require '../footer.php';
    exit;
}

$orderId = $_GET["orderId"];

// process Form: Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $updateStmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
    $updateStmt->execute([':status' => $_POST['status'], ':id' => $orderId]);
    $StatusMessage = "Order status successfully updated to " . htmlspecialchars($_POST['status']) . ".";
}

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

// fetch all products in order
$stmt = $pdo->prepare("
    SELECT products.name, products.price, orderItems.quantity
    FROM orderItems
    JOIN products ON orderItems.productId = products.id
    WHERE orderItems.orderId = ?
");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dateTime = new DateTime($order['createdAt']);

// html
require '../header.php';
?>

<h2><strong>Order #</strong><?php echo htmlspecialchars($order['orderNumber']); ?></h2>

<?php if ($StatusMessage) echo "<p style='color:green;'><em>$StatusMessage</em></p>"; ?>

<p><strong>Order Date:</strong> <?php echo $dateTime->format("F j, Y g:i A"); ?></p>
<p><strong>Customer Email:</strong> <?php echo htmlspecialchars($order['custEmail']); ?></p>

<form method="POST" action="orderDetails.php?orderId=<?php echo htmlspecialchars($orderId); ?>">
    <label for="status"><strong>Status:</strong></label>
    <select name="status" id="status">
        <option value="Processing" <?php if($order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
        <option value="Shipping" <?php if($order['status'] == 'Shipping') echo 'selected'; ?>>Shipping</option>
        <option value="Delivered" <?php if($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
    </select>
    <button type="submit">Update Status</button>
</form>
<br>

<h3>Items Ordered</h3>
<?php if (count($orderItems) === 0) { ?>
    <p>No items in this order.</p>
<?php } else { ?>
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
<?php } ?>

<br>
<p><a href="orders.php">Back to All Orders</a></p>

<?php require '../footer.php'; ?>