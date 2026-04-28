<?php 

    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);

    $title = "Shopping Cart"; 

    require '../header.php'; 
    require '../database/database.php'; 


    $sessionId = $_SESSION["sessionId"];

    $stmt = $pdo->prepare("
        SELECT shoppingCart.id, shoppingCart.productId, 
        shoppingCart.quantity, products.name, products.price
        FROM shoppingCart
        JOIN products ON shoppingCart.productId = products.id
        WHERE shoppingCart.sessionId = ?
    ");
    $stmt->execute([$sessionId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2><strong>Shopping Cart</strong></h2>

    
    <?php if (count($cartItems) === 0) { ?>
        <p>No items in your cart.</p>
        <p><a href="../products.php">Continue Shopping</a></p>
    <?php } else { ?>
        <table> 
            <tr> 
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Delete</th>
            </tr>

            <?php 
                 $total = 0; 
                 foreach ($cartItems as $cartItem) { 
                    $subtotal = $cartItem['price'] * $cartItem['quantity'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo $cartItem['name']; ?></td>
                        <td>$<?php echo number_format($cartItem['price'], 2); ?></td>
                        <td> 
                            <form method="post" action="updateCart.php" style="display:inline">
                                <input type="hidden" name="cartId" value="<?php echo $cartItem['id'];?>">
                                <input type="hidden" name="quantity" value="<?php echo max(0, $cartItem['quantity'] - 1);?>">
                                <input type="submit" value="-">
                            </form>
                            <?php echo $cartItem['quantity'];?> 
                            <form method="post" action="updateCart.php" style="display:inline">
                                <input type="hidden" name="cartId" value="<?php echo $cartItem['id'];?>">
                                <input type="hidden" name="quantity" value="<?php echo $cartItem['quantity'] + 1;?>">
                                <input type="submit" value="+"> 
                            </form>
                        </td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="post" action="updateCart.php">
                                <input type="hidden" name="cartId" value="<?php echo $cartItem['id'];?>">
                                <input type="hidden" name="quantity" value="0">
                                <input type="submit" value="Delete">
                            </form>
                        </td>
                    </tr>
            <?php } ?>

                 <tr> 
                    <td colspan="3"><strong>Total</strong></td>
                    <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                 </tr>
        </table>

            <p><a href="processOrder.php">Checkout</a></p>
            <p><a href="../products.php">Continue Shopping</a></p>
    
    <?php } ?>

<?php require '../footer.php'; ?>