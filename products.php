<?php 

    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);
    
    $title = "Products";
    require 'header.php'; 
    require 'database/database.php'; 
    // get all products
    $stmt = $pdo->query("SELECT * FROM products ORDER BY name"); 
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Products</h2>

<!-- loops through and displays all products with links to more information -->
<?php foreach ($products as $product) { ?>
     <section>
        <h3><?php echo $product['name']; ?></h3>

        <img 
            src="<?php echo htmlspecialchars($product['imageUrl'] ?: 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg'); ?>" 
            alt="<?php echo htmlspecialchars($product['name']); ?>" 
            width="200"  
            height="200"
        >

        <p> Price: $<?php echo number_format($product['price'], 2); ?></p>
        <?php 
            if ($product["stock"] === 0) {
                echo "<p>Out of Stock</p>";
                echo "<p>Product Unavailable</p>";
            } else {
                echo "<p> Stock: " . $product['stock'] . "</p>";
                echo '<p> <a href="user/productDetails.php?id=' . $product['id'] . '">View Details</a></p>';
            } ?>
 
    </section>

    <hr>
<?php } ?>
<?php require 'footer.php'; ?>