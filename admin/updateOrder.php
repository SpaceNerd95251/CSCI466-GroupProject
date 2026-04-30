<?php 

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    //if (empty($_SESSION['isAdmin'])) {
    //    header('Location: login.php'); 
    //    exit; 
    //}
   // if (!$orderId || !$status) {
    //    header('Location: orders.php');
    //    exit;
    //}

    require '../database/database.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') { 
        $orderId = $_POST['orderId']; 
        $status = $_POST['status']; 
        $notes = $_POST['notes'] ?? null;
            $stmt = $pdo->prepare("
            UPDATE orders 
            SET status = ?, notes = ? 
            WHERE id = ?
            ");
            $stmt->execute([$status, $notes, $orderId]);
        }
    // returns to the corresponding orderDetails page once updates are finished 
    header("Location: orderDetails.php?id=" . $orderId); 
    exit; 
?>