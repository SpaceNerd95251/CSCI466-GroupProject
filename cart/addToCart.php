<?php 
    session_start();
    require '../database/database.php';

    if(!isset($_SESSION['sessionId'])) { 
        $_SESSION['sessionId'] = session_id(); 
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST') { 
        $productId = $_POST['productId'];
        $quantity = $_POST['quantity'];
        $sessionId = $_SESSION['sessionId'];

        // prevents manually entering a negative quantity
        if($quantity < 1) { 
            $quantity = 1; 
        }

        // checks if product is already in cart (UPDATE vs. INSERT)
        $stmt = $pdo->prepare('
        SELECT id, quantity 
        FROM shoppingCart 
        WHERE sessionId = ? 
        AND productId = ?
        ');
        $stmt->execute([$sessionId, $productId]);
        $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if($cartItem) { 
            $newQuantity = $cartItem['quantity'] + $quantity; 

            // updates shopping cart table with new quantity
            $stmt = $pdo->prepare('
            UPDATE shoppingCart 
            SET quantity = ? 
            WHERE id = ?
            AND sessionId = ?
            ');
            $stmt->execute([$newQuantity, $cartItem['id'], $sessionId]);
        } else { 
            $stmt = $pdo->prepare('
            INSERT INTO shoppingCart 
            (sessionId, productId, quantity)
            VALUES (?, ?, ?)
            ');
            $stmt->execute([$sessionId, $productId, $quantity]);
        }
    }

    header("Location: ../products.php");
    exit;

