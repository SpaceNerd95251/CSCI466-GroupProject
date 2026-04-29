<?php 

    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);

    $title = "Product Details"; 
    require '../database/database.php'; 

    if(!isset($_GET['id'])) { 
        require '../header.php'; 
        echo "<p>Invalid product.</p>";
        require '../footer.php';
        exit; 
    }
    // gets id from products
    $id = $_GET["id"];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?"); 
    $stmt->execute([$id]); 
    $product = $stmt->fetch(PDO::FETCH_ASSOC); 

    if (!$product) { 
        require '../header.php'; 
        echo "<p>Product not found</p>";
        require '../footer.php';
        exit; 
    }
    require '../header.php'; 
?>

<h2><?php echo $product['name']; ?></h2>
    
    <img 
        src="<?php echo htmlspecialchars($product['imageUrl'] ?: 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg'); ?>" 
        alt="<?php echo $product['name']; ?>" 
        width="200"  
        height="200"
    >
    <p> Description: <?php echo $product['description']; ?></p>
    <p> Price: $<?php echo number_format($product['price'], 2); ?></p>
    <p> Stock: <?php echo $product['stock']; ?></p>

     <form method="post" action="../cart/addToCart.php">
        <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
        <input type="number" name="quantity" value="1" min="1">
        <input type="submit" value="Add to Cart">
     </form> 

<?php require '../footer.php'; ?>