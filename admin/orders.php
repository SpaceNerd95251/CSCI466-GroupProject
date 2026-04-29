<?php
// to show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$StatusMessage = "";

// admin login
$correctPassword = "password123";

// login form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_pass'])) {
    if ($_POST['admin_pass'] === $correctPassword) {
        $_SESSION['isAdmin'] = true;
    }
}

// If not logged in go to login page exit
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

// get data
require '../database/database.php';
$title = "All Customer Orders - Admin View";

$orders = [];
try {
    // fetch all orders
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY createdAt DESC");
    if ($stmt) {
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $StatusMessage = "Database Error: " . $e->getMessage();
}

// html
require '../header.php';
?>

<h2>All Customer Orders</h2>

<?php if ($StatusMessage) echo "<p style='color:red;'>$StatusMessage</p>"; ?>

<table border="1">
    <tr>
        <th>Order ID</th>
        <th>Order Number</th>
        <th>Customer Email</th>
        <th>Status</th>
        <th>Date Placed</th>
        <th>Action</th>
    </tr>
    <?php 
    if (!empty($orders)) {
        foreach ($orders as $row) { 
            $dateString = !empty($row['createdAt']) ? $row['createdAt'] : 'now';
            $date = new DateTime($dateString);
        ?>
             <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['orderNumber']); ?></td>
                <td><?php echo htmlspecialchars($row['custEmail']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo $date->format("M j, Y"); ?></td>
                <td><a href="orderDetails.php?orderId=<?php echo htmlspecialchars($row['id']); ?>">View Details</a></td>
             </tr>
        <?php 
        } 
    } else {
        echo "<tr><td colspan='6'>No orders found.</td></tr>";
    }
    ?>
</table>

<?php
require '../footer.php';
?>