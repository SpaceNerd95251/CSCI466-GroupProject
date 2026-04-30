<?php
// to show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If not logged in go to login page exit
if (empty($_SESSION['isAdmin'])) {
    header('Location: login.php'); 
    exit; 
}

// get data
require '../database/database.php';
$title = "Orders - Admin View";

$stmt = $pdo->query("
    SELECT * FROM orders
    ORDER BY createdAt DESC
    ");

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
// html
require '../header.php';
?>

<h2>All Customer Orders</h2>

<?php if (count($orders) === 0) { ?>
    <p>No orders found.</p>
<?php } else { ?>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Order Number</th>
            <th>Customer Email</th>
            <th>Status</th>
            <th>Date Placed</th>
            <th>View Details</th>
        </tr>
        <?php 
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
                <td><a href="orderDetails.php?id=<?php echo htmlspecialchars($row['id']); ?>">View Details</a></td>
             </tr>
        <?php } ?>
    </table>

<?php } ?>

<?php
require '../footer.php';
?>