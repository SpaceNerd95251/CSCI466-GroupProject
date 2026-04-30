<?php 

    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // admin password is hardcoded 
    // password is "password"
    $password = "password"; 
    $title = "Admin Login"; 
    require '../database/database.php';
    // for a correctly formatting error message 
    $error = ""; 

    // checks if the form was submitted, then redirects to orderDetails.php
    if($_SERVER["REQUEST_METHOD"] == 'POST') { 
        $password = $_POST['password'];

        if($password === "password") { 
             $_SESSION['isAdmin'] = true;
            // goes to orders.php
            header('Location: orders.php');
            exit; 
        }else { 
            $error = "Password incorrect. Please try again.";
        }
    }
    require '../header.php';
?>
<h2>Admin Login</h2>
    <?php if ($error != "") { 
        echo "<p>$error</p>";
    }
    ?>
    <form method="post"> 
        <p>
            Password: (hint: it's 'password') <br>
            <input type="password" name="password" required> 
        </p>
        <p> 
            <input type="submit" value="Login">
        </p>
    </form>

<?php require '../footer.php'; ?>
