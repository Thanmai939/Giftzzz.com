<?php
session_start();
$db_name = "mysql:host=localhost;dbname=giftz";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username, $password);

$mail = $_SESSION['email'];
?>

<?php

$db_name = "mysql:host=localhost;dbname=giftz";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username, $password);


$user_id = $_SESSION['email'];

if(!isset($user_id)){
   header('location:');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .' '. $_POST['city'] .' '. $_POST['state'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $currentDate = new DateTime();
   $placed_on = $currentDate->format('Y-m-d');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE mail = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

   if($cart_total == 0){
      $message[] = 'your cart is empty';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'order placed already!';
   }else{
      $insert_order = $conn->prepare("INSERT INTO `orders`( name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([ $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE mail = ?");
      $delete_cart->execute([$user_id]);
      $message[] = 'order placed successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
   <style>
    @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
      @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@1,300&family=Playfair+Display&display=swap');
      body {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
         background-color: #f5f5f5;
      }
      /*header starts*/
      header 
      {
         margin-top : -25px;
         height: 140px;
         background-color: #b82e63;
        
         width: 1530px;
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
        display: flex;
         margin-left: 1100px;
         
      }
      .icons a 
      {
         text-decoration:none;
         color: white;
      }
      /*header ends*/

      .disabled {
         pointer-events: none;
         opacity: 0.5;
      }
      .empty {
         font-weight: bold;
         text-align: center;
         padding: 20px;
         margin-left:400px;
         width: 400px;
         font-family: 'Playfair Display', serif;
         font-size: 25px;
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

      .box .sub-total 
      {
         font-size: 1.2rem;
         margin-top: 1rem;
         font-weight: 700;
         background-color:pink;
         border: none;
         border-radius: 5px;
      }

      .cart-total {
         margin-top: 3rem;
         text-align: center;
      }

      .cart-total p {
         font-size: 1.8rem;
         margin-bottom: 1rem;
         font-family: 'Playfair Display', serif;
         font-weight: bold;
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
      /* Product cards design ending*/

      /*Form designing*/
      @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
      @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:ital,wght@0,700;1,700&family=Playfair+Display:wght@600&family=Staatliches&display=swap');
      @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:ital,wght@0,700;1,700&family=Playfair+Display:wght@600&display=swap');
      .container {
         width: 1150px;
         margin: 0 auto;
         padding: 20px;
         height: 630px;
         background-color: rgb(87, 45, 85);
         margin-bottom: 20px;
      }

      .checkout-orders {
         background-color: rgba(255, 255, 255, 0.8);
         padding: 40px;
         border-radius: 10px;
         box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
         
      }

      .checkout-orders .hd h3 {
         margin: 10px 10px 60px 10px;
         font-size: 30px;
         font-weight: bold;
         text-align: center;
         margin-bottom: 60px;
         font-family: 'Roboto Slab', serif;
      }

      .form-row {
         display: flex;
         justify-content: space-between;
         margin-bottom: 20px;
         margin-top: 30px;
      }

      .inputBox {
         flex: 0 0 48%;
         position: relative;
      }

      .inputBox input,
      .inputBox select {
         width: 80%;
         padding: 10px;
         border: 1px solid #ccc;
         border-radius: 10px;
         font-size: 16px;
         background-color: rgba(255, 255, 255, 0.2);
         outline: none;
         margin-top: 20px;
         margin-left: 10px;
      }

      .inputBox input:focus,
      .inputBox select:focus {
         background-color: rgba(255, 255, 255, 0.4);
      }

      .inputBox label {
         position: absolute;
         top: -10px;
         left: 15px;
         pointer-events: none;
         transition: 0.3s;
         color: #100855;
         font-size: 16px;
         font-family: 'Playfair Display', serif;
         margin-bottom: 15px;
         font-weight: 500;
      }

      .inputBox input:focus + label,
      .inputBox select:focus + label,
      .inputBox input:not(:placeholder-shown) + label,
      .inputBox select:not(:placeholder-shown) + label {
         top: -10px;
         font-size: 12px;
         color: #4caf50;
      }

      .inputBox input[type="submit"] {
         cursor: pointer;
         background-color: #4caf50;
         color: #fff;
         border: none;
         border-radius: 35px;
         padding: 10px;
         font-size: 16px;
         width: 150px;
         transition: background-color 0.3s;
         margin: 0 auto;
         display: block;
         margin-top: 20px;
         margin-left: 450px;
         
      }

      .inputBox input[type="submit"] 
      {
            background-color: #45a049;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        /*.orderbtn
        {
            background-color: rgb(217, 136, 31);
            border-radius: 30px;
            color: #fff;
            font-size: 15px;
            padding: 15px 30px;
            border: none;
            cursor: pointer;
            margin-top: 30px;
        }*/
        .close-btn 
        {
            font-size: 40px;
            margin-top: -30px;
            float: right;
            cursor: pointer;
        }

   </style>

</head>
<body>

<header>
   <center><h1 class="title" style="font-size:40px;">Cart Items</h1></center>

   <div class="icons">
       <span class="material-symbols-outlined" style="margin-top: 60px; color:white;"><a href="./afterlogin.html">home</a></span>
       <span class="material-symbols-outlined" style="margin-top: 60px; color:white;"><a href="./cartdisplay.php">fast_rewind</a></span>
   </div>
</header>

<section class="shopping-cart">
   <div class="box-container" id="box-container">

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
      <div class="price" style="font-weight:bold; font-size:17px;">Qty:<?= $fetch_cart['quantity'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$fetch_cart['price']; ?>/-</div>
      <div class="sub-total"><span><?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
   </form>
   <?php
      $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty" style="">your cart is empty</p>';
   }
   ?>
   </div>
   <footer>
      <div class="cart-total">
            <p class="cart-total">grand total : <span><?= $grand_total; ?>/-</span></p>
      </div>

      <!--<div>
         <center><button type="submit" class="orderbtn" onclick="showForm()">Place the Order</button></center>
      </div>-->
   </footer>
</section>

<!--Form begins-->
<div class="container" id="container">
   <section class="checkout-orders">

      <div class="hd">
        <h3>Place Your Order</h3>
      </div>
      <!--<span class="close-btn" onclick="closeForm()">&times;</span>-->
      <form action="" method="post" onsubmit="redirectToPage()" enctype="multipart/form-data">
        <div class="form-row">
            <div class="inputBox">
                <label for="itemName" class="lbl">Name</label>
                <input type="text" id="name" name="name" required>
             </div>
             <div class="inputBox">
                <label for="email" class="lbl">Email</label>
                <input type="email" id="email" name="email" required>
             </div>
          </div>

        <div class="form-row">
            <div class="inputBox">
                <label for="phone" class="lbl">Contact</label>
                <input type="number" id="number" name="number" required>
            </div>
            <div class="inputBox">
                <label for="method" class="lbl">Payment Method</label>
                <select name="method" id="method" style="width:83%;" required>
                    <option value=""></option>
                    <option value="cash on delivery">Cash on Delivery</option>
                    <option value="credit card">Credit Card</option>
                    <option value="paytm">Paytm</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="inputBox">
                <label for="address" class="lbl">Address</label>
                <input type="text" id="flat" name="flat" required>
            </div>
            <div class="inputBox">
                <label for="city" class="lbl">City</label>
                <input type="text" id="city" name="city" required>
            </div>
        </div>

        <div class="form-row">
            <div class="inputBox">
                <label for="state" class="lbl">State</label>
                <input type="text" id="state" name="state" required>
            </div>
            <div class="inputBox">
                <label for="pin" class="lbl">Pincode</label>
                <input type="number" id="pin_code" name="pin_code" required>
            </div>
        </div>

        <div class="form-row">
            <div class="inputBox">
              <input type="submit" name="order" class="btn " value="Save">
            </div>
        </div>
       </form>
    </section>
  
  </div>


<!--<script >
    function showForm() 
    {
        document.getElementById("container").style.display = "block";
        document.getElementById("box-container").style.display = "none";
    }

    function closeForm() 
    {
        document.getElementById("box-container").style.display = "none";
        document.getElementById("content").style.display = "block";
    }
    function redirectToPage()
    {
        alert("Order placed")
    }
</script>-->

</body>
</html>