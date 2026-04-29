<?php 
    session_start();
    require '../database/database.php';

    if(!isset($_SESSION['sessionId'])) { 
        $_SESSION['sessionId'] = session_id(); 
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST') { 
        $cartId = $_POST['cartId']; 
        $quantity = $_POST['quantity']; 
        $sessionId = $_SESSION['sessionId']; 

        if($quantity <= 0) { 
            $stmt = $pdo->prepare("
                DELETE FROM shoppingCart
                WHERE id = ?
                AND sessionId = ?
            ");
            $stmt->execute([$cartId, $sessionId]);
        } else { 
            $stmt = $pdo->prepare("
            UPDATE shoppingCart 
            SET quantity = ? 
            WHERE id = ? 
            AND sessionId = ?
            ");
            $stmt->execute([$quantity, $cartId, $sessionId]);
        }
    }
    // returns to cart.php once updates are finished 
    header("location: cart.php"); 
    exit; 
?>