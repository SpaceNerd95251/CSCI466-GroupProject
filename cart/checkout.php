<?php 

    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);

    $title = "Checkout"; 

    require '../header.php'; 
    require '../database/database.php'; 

    // session is started in header file 
    $sessionId = $_SESSION["sessionId"];

    $stmt = $pdo->prepare("
        SELECT shoppingCart.id, shoppingCart.productId, 
        shoppingCart.quantity, products.name, products.price, products.stock
        FROM shoppingCart
        JOIN products ON shoppingCart.productId = products.id
        WHERE shoppingCart.sessionId = ?
    ");
    $stmt->execute([$sessionId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<h2><strong>Checkout</strong></h2> 

<h3>Order Summary</h3>

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
                        <td><?php echo max($cartItem['stock'], $cartItem['quantity']);?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>

            <?php } ?>

                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                </table>

                <h3><strong>Shipping Information</strong></h3>

                <form method="POST" action="processOrder.php"> 
                    <p>
                        Name:
                        <input type="text" name="name" required>
                    </p>
                    <p> 
                        Email:
                        <input type="email" name="email" required>
                    </p>
                    <p> 
                        Street Address:
                        <input type="text" name="streetAddress" required>
                    </p>
                    <p> 
                        City:
                        <input type="text" name="city"  required>
                    </p>
                    <p> 
                        State:
                        <input type="text" name="state" placeholder="XX" maxlength="2" required>
                    </p>
                    <p> 
                        Zip Code:
                        <input type="text" name="zipcode" maxlength="5" required>
                    </p>

                    <h3>Payment Information</h3>
                        <!-- note: shipping address will be used as billing address -->
                    <p> 
                        Card Number:
                        <input type="text" name="cardNumber" required>
                    </p>
                    <p> 
                        Expiration Date:
                        <input type="text" name="expirationDate" placeholder="MM/YY" maxlength="5" required>
                    </p>
                    <p> 
                        CVV:
                        <input type="text" name="cvv" maxlength="3" required> 
                    </p>
                    <p>
                        <input type="submit" value="Place Order">
                    </p>
                </form>

                <p><a href="cart.php">Back to Cart</a></p>
    <?php } ?>
    <?php require '../footer.php'; ?>
                     