<?php 
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
            src="<?php echo htmlspecialchars($product['image'] ?: 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg'); ?>" 
            alt="<?php echo $product['name']; ?>" 
            width="200"  
            height="200"
        >

        <p> Price: $<?php echo number_format($product['price'], 2); ?></p>
        <p> Stock: <?php echo $product['stock']; ?></p>
        <p> 
            <a href="products.php?id=<?php echo $product['id']?>">View Details</a>
        </p>
    </section>

    <hr>
<?php } ?>
<?php require 'footer.php'; ?>