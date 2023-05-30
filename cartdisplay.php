<?php
session_start();
$db_name = "mysql:host=localhost;dbname=giftz";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username, $password);

$mail = $_SESSION['email'];

if(!isset($mail)){
   header('location:loginregister.html'); //change this to our login page
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE name= ?");
   $delete_cart_item->execute([$delete_id]);
   header('location:cartdisplay.php');
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE mail= ?");
   $delete_cart_item->execute([$mail]);
   header('location:cartdisplay.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE name = ?");
   $update_qty->execute([$p_qty, $cart_id]);
   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

   <!-- custom css file link  -->
   <!--<link rel="stylesheet" href="css/style.css">-->
   <style>
      /* Custom CSS for product cards */
      @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
      @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@1,300&family=Playfair+Display&display=swap');
      body {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
      }
      header 
      {
         margin-top : -25px;
         height: 140px;
         background-color: #b82e63;
      }

      header h1 
      {
         float:left;
         margin-top: 60px;
         font-family: 'Playfair Display', serif;
         font-weight: 1000;
         margin-left: 40px;
         position: fixed;
         color: white;
      }

      .material-symbols-outlined 
      {
         font-variation-settings:
         'FILL' 0,
         'wght' 400,
         'GRAD' 0,
         'opsz' 48;
         font-size: 2.5rem;
         color: rgb(194, 126, 37);
         padding: 10px;
      }

      .icons 
      {
         margin-left: 1150px;
         justify-content: space-between;
      }
      .icons a 
      {
         text-decoration:none;
         color: white;
      }
      .shopping-cart {
         padding: 2rem;
      }

      .title {
         font-size: 2rem;
         margin-bottom: 2rem;
         text-align: center;
      }

      .box-container {
         margin-left:100px;
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(400px,100px));
         gap: 2rem;
      }

      .box {
         width: 300px;
         background-color: #fff;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         text-align: center;
         padding: 2rem;
         border-radius: 2rem;
         transition: all 0.3s ease;
         position: relative;
      }

      .box img {
         max-width: 100%;
         height: auto;
         margin-bottom: 1rem;
      }

      .box .name {
         font-size: 1.2rem;
         margin-bottom: 0.5rem;
         font-family: 'Playfair Display', serif;
         font-weight: bold;
      }

      .box .price {
         font-size: 1.2rem;
         margin-bottom: 1rem;
      }

      .box .qty {
         width: 50px;
         padding: 0.5rem;
         border: 1px solid #ccc;
         margin-right: 1rem;
         text-align: center;
      }

      .box .option-btn {
         padding: 0.5rem 1rem;
         background-color: #9e398d;
         color: #fff;
         border: none;
         cursor: pointer;
      }

      .box .sub-total {
         font-size: 1.2rem;
         margin-top: 1rem;
         font-weight: 700;
         background-color:pink;
         border: none;
         border-radius: 5px;
      }

      .cart-total {
         margin-top: 2rem;
         text-align: center;
      }

      .cart-total p {
         font-size: 1.2rem;
         margin-bottom: 1rem;
      }

      .cart-total .option-btn,
      .cart-total .delete-btn,
      .cart-total .btn {
         padding: 0.5rem 1rem;
         background-color: #9e398d;
         color: #fff;
         border: none;
         cursor: pointer;
         margin-right: 1rem;
         text-decoration: none;
      }

      .cart-total .delete-btn.disabled,
      .cart-total .btn.disabled {
         background-color: #9e398d;
         pointer-events: none;
      }

      .empty {
         text-align: center;
         font-size: 2rem;
         margin-top: 200px;
         margin-left: 500px;
         width: 300px;
         color: black;
         font-family: 'Playfair Display', serif;
         font-weight: bold;
      }

      .box .fa-solid.fa-trash 
      {
         color: #888;
         font-size: 1.5rem;
         margin-right: 5px;
         margin-top:2px;
      }

      .dd 
      {
         position: relative;
         display: inline-block;
      }

      .dd-toggle {
         padding: 0.5rem 1rem;
         background-color: #9e398d;
         color: #fff;
         border: none;
         cursor: pointer;
      }

      .dd-menu {
         position: absolute;
         top: 100%;
         left: 0;
         z-index: 1;
         display: none;
         padding: 10px;
         margin: 0;
         background-color: #fff;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         border-radius: 0.5rem;
         width:110px;
      }

      .dd-menu a {
         display: block;
         padding: 0.5rem 1rem;
         color: #000;
         text-decoration: none;
      }

      .dd-menu a.disabled {
         pointer-events:visiblePainted;
         
      }

      .dd:hover .dd-menu {
         display: block;
      }
      .dd-menu a
      {
         font-size: 15px;
         text-decoration: solid;
         font-weight: 1000;
      }
      .dd-menu a:hover 
      {
         color:#9e398d;
      }

      .cart-total p {
         text-align: center;
         font-size: 2rem;
         margin-top: 80px;
         margin-left: 600px;
         width: 300px;
         color: black;
         font-family: 'Playfair Display', serif;
         font-weight: bold;
      }
   </style>
</head>
<body>
   
<header>
   <h1 class="title"> Welcome to your Cart</h1>

   <div class="icons">
            <span class="material-symbols-outlined" style="margin-top:50px;"><a href="afterlogin.html">home</a></span>
            <span class="material-symbols-outlined"><a href="./Myaccount.php">account_circle</a></span>
            <span class="material-symbols-outlined"><a href="./logout.php">logout</a></span>
            
            <div class="dd">
               <span class="material-symbols-outlined" style="color:white";>reorder</span>
               <div class="dd-menu">
                  <a href="afterlogin.html" class="dropdown-item">Continue Shopping</a>
                  <a href="cartdisplay.php?delete_all" class="dropdown-item <?= ($grand_total > 1)?'':'disabled'; ?>">Clear All</a>
                  <a href="checkout.php" class="dropdown-item <?= ($grand_total > 1)?'':'disabled'; ?>">Checkout</a>
               </div>
            </div>
            
    </div>
</header>
<section class="shopping-cart">

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE mail = ?");
      $select_cart->execute([$mail]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="POST" class="box">
      
     <!-- <a href="view_page.php?pid=" class="fas fa-eye"></a> -->
      <img src="<?= $fetch_cart['image']; ?>" alt="">
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <div class="price"><?= $fetch_cart['price']; ?>/-</div>
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['name']; ?>">
      <div class="flex-btn">
         <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty">&nbsp;&nbsp;
         <input type="submit" value="update" name="update_qty" class="option-btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <a href="cartdisplay.php?delete=<?= $fetch_cart['name']; ?>" class="fa-solid fa-trash" onclick="return confirm('delete this from cart?');"></a>
      </div>
      <div class="sub-total"><span><?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
   </form>
   <?php
      $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   </div>
   <footer>
      <div class="cart-total">
            <p>grand total : <span><?= $grand_total; ?>/-</span></p>
      </div>
   </footer>



</section>

</body>
</html>