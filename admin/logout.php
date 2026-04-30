<?php 
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['isAdmin']);
    header('Location: ../products.php');
    exit; 
?>
