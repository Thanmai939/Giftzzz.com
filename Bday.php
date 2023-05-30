
<?php

$db_name = "mysql:host=localhost;dbname=giftz";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username,$password);


session_start();

$mail = $_SESSION['email'];

if(!isset($mail)){
   header('location:loginregister.html');
};



if(isset($_POST['add_to_cart'])){

   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? ");
   $check_cart_numbers->execute([$p_name]);

   if($check_cart_numbers->rowCount() > 0)
   {
      $message[] = 'already added to cart!';
   }
   else
   {

      $insert_cart = $conn->prepare("INSERT INTO `cart`(mail,name, price, quantity, image) VALUES(?,?,?,?,?)");
      $insert_cart->execute([$mail, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }

}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Birthday</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      :root
        {
            --pink:#e84393;
        }
        *
        {
            margin:0;
            padding:0;
            box-sizing: border-box;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            border:none;
            text-decoration: none;
            text-transform: capitalize;
            transition: .2s linear;
        }
        html
        {
            font-size: 60%;
            scroll-behavior: smooth;
            scroll-padding-top: 6rem;
            overflow-x: hidden;
        }
        .first
        {
            position: fixed;
            z-index: 5;
            top: 0;left: 0;right: 0;
            background: #fff;
            padding: 2rem 9%;
            display:flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
        }
        .first .logo
        {
            font-size: 3rem;
            color: #333;
            font-weight: bolder;
        }
        .first .logo span
        {
            color: var(--pink) ;
        }
        .first .navbar
        {
        margin-right: -100px;
        }
        .first .navbar a
        {
            font-size: 2rem;
            padding: 0 1.5rem;
            color: #666;
        }
        .first .navbar a:hover
        {
            color:var(--pink);
        }
        .first .div
        {
            margin-right: 40px;
            margin-top: 20px;
        }
        .first .icons a
        {
            position: relative;
            font-size: 3rem;
            color: #333;
            margin-left: 1.5rem;
            margin-top: 10px;
        }

        .first .icons a:hover
        {
            color:var(--pink);
        }
        /*button
        {
            background-color: #e84393;
            color: white;
            padding: 14px 18px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            font-size: 1.5rem;
        }*/

        .search-bar 
        {
            width: 450px;
            display: -webkit-box;
            display: -ms-flexbox; 
            display: flex;
            position: relative;
            margin-left: 5px;
        }
        .search-bar input 
        {
            margin-left: -50px;
            font-size: 14px;
            padding: 10px;
            border: 1px solid #dcdbdb;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            border-radius: 25px
        }
        .search-bar input:focus 
        {
            outline:#000;
        }

        input[type=search]::-webkit-search-cancel-button,input[type=search]::-webkit-search-decoration,input[type=search]::-webkit-search-results-button,input[type=search]::-webkit-search-results-decoration
        {
            display: none
        }

        .search-bar .search-btn 
        {
            position: absolute;
            font-size: 17px;
            top: 4px;
            right: 10px;
            border-radius: 2px;
            padding: 5px;
            background-color:#fff;
            color: #000;
        }


        .myDropdown 
        {
            position: absolute;
            z-index: 1;
            background-color: #fffdfd;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            right: 130px;
            margin-top: 20px;
            border-radius: 10px;
        }


        .dropcolor{
            background: white;
        }


        .myDropdown a {
            padding: 12px 16px;
            text-decoration: none;
            display: block;

        }


        .myDropdown .dropcolor:hover {
            color: #4eb1f3;

        }

        .fa-user{
            margin-right:1rem;
        }
        .hero1 img
        {
          margin-top: 90px;
          margin-left: 5px;
          height: 500px;
          width:1510px;
        }
        /* Featured products section styles */
        .featured-products 
        {
          padding: 2rem;
        }
        
        .featured-products h2 
        {
          font-size: 2rem;
          margin-bottom: 2rem;
          text-align: center;
        }
        
        .product-grid 
        {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 2rem;
        }
        
        .product-card 
        {
          background-color: #fff;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          text-align: center;
          padding: 2rem;
          border-radius: 2rem;
          transition: all 0.3s ease;
        }
        
        .product-card:hover 
        {
          transform: translateY(-5px);
        }
        
        .product-card img 
        {
          max-width: 100%;
          height: auto;
        }
        
        .product-card h3
        {
          font-size: 2rem;
          margin-top: 1rem;
          padding: 5px;
        }
        
        .product-card p 
        {
          font-size: 2rem;
          font-weight: bold;
          margin-top: 0.5rem;
          padding: 5px;
        }
        
        .product-card .btn
        {
          padding: 0.75rem 2rem;
          background-color: #e84393;
          
          color: #fff;
          font-size: 1.5rem;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          margin-top: 1rem;
          transition: all 0.3s ease;
        }
        
        .product-card .btn:hover 
        {
          background-color: #f20075;
        }
        footer
        {
            background-color: #c06587;
        }
        .footercontainer
        {
            width:100%;
            padding:70px 30px 20px;
        }
        .socialIcons
        {
            display:flex;
            justify-content:center;
        }
        .socialIcons a
        {
            text-decoration:none;
            padding:10px;
            margin:10px;

        }
        .socialIcons a
        {
            font-size: 4em;
            color:black;
            opacity:0.9;
        }
        .socialIcons a:hover
        {
            color:white;
            transition:0.5s;

        }
        .footerNav
        {
            margin:30px 0;
            margin-top: 10px;
        }
        .footerNav ul
        {
            display:flex;
            justify-content:center;
            list-style-type: none;
        }
        .footerNav ul li a
        {
            color:white;
            margin:20px;
            text-decoration:none;
            font-size:1.3em;
            opacity:0.7;
            transition:0.5s
        }
        .footerNav ul li a:hover

        {
            opacity:1;

        }
        .footerBottom
        {
            background-color: #c06587;
            padding:20px;
            text-align: center;
        }
        .footerBottom p
        {
            color:rgb(1, 1, 1);
        }
        .designer
        {
            opacity:0.7;
            text-transform:uppercase;
            letter-spacing:1px ;
            font-weight:400 ;
            margin:0px 5px;
        }
        @media(max-width:700px)
        {
            .footerNav ul
            {
                flex-direction: column;
            }
            .footerNav ul li
            {
                width:100%;
                text-align: center;
                margin:10px;
            }
        }
        .qty 
        {
            height: 30px;
            width: 50px;
            text-align: center;
            margin-right: 10px;
            font-size: 16px; 
            padding: 8px; 
            border: 2px solid #ccc; 
            border-radius: 4px; 
            box-shadow: 1px 1px 2px #ccc;  
        }
     
    </style>
  </head>


  <body>
  <header class="first">
    <!--header section starts-->
        <a href="#" class="logo" style="font-size: 50px;">Giftzzz<span>.</span>com</a>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <div class="search-bar" style="margin-left: -10px;">
            <input type="search"  name="search_prd" class="search_prd search-across-app ng-pristine ng-valid ng-valid-maxlength ng-touched" id="search_prd" maxlength="255" placeholder="Search Gifts..." tabindex="0" autocomplete="off" >
        
            <button class="search-btn" id="search_submit" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
        </div>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
        <div class="icons">
            <a href="./afterlogin.html" class="fas fa-home"></a>
            <a onclick="myFunc()" id="profile" class='fas fa-user'> </a>
            <a class="fas fa-shopping-cart" href="cartdisplay.php"></a>&nbsp; &nbsp;
            
            
            <div class="myDropdown" style="display:none;">
                <a class="dropcolor" href="./Myaccount.php" style="font-size: 20px;">Account</a>
                <a onclick="myFunction()" class="dropcolor" href="./Giftzzcom.html" style="font-size: 20px;">Logout</a>
            </div>
        </div>
    <!--header section ends-->
  </header>

   <section class="hero1">
      <img src="birthdaybanner.png">
    </section>

    <section class="featured-products">
      <div class="product-grid">

        <?php
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'giftz';

        $conn = mysqli_connect($host, $username, $password, $dbname);

        if (!$conn) {
          echo 'Error Code: ' . mysqli_connect_errno() . '<br>';
          echo 'Error Message: ' . mysqli_connect_error() . '<br>';
          exit();
        }

        $query = 'SELECT id,name,filename,price
            FROM birthday
            ORDER BY name';

        $result = mysqli_query($conn, $query);

        while ($record = mysqli_fetch_assoc($result)) 
        {  
            echo '<div class="product-card">';
          echo '<img src="birthday/'.$record['filename'].'" >';
          echo '<h3>'.$record['name'].'</h3>';
          echo '<p class="price"><span class="WebRupee">Rs.</span>'.$record['price'].'</p>';
          echo '
        
                <form action="" method="POST" class="box">
                        <input type="hidden" name="p_name" class="nm" value="'.$record['name'].'">
                        <input type="hidden" name="p_price" value="'. $record['price'].'">
                        <input type="hidden" name="p_image" value="birthday/'. $record['filename'].'">
                        <input type="number" min="1" value="1" name="p_qty" class="qty">
                        <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                        </div>
                </form>';
        }

        ?>
        
      </div>
    </section>

    <footer>
      <div class="footerContainer">
          <div class="socialIcons">
              <a href="#"><i class="fa-brands fa-facebook"></i></a>
              <a href="#"><i class="fa-brands fa-instagram"></i></a>
              <a href="#"><i class="fa-brands fa-twitter"></i></a>
              <a href="#"><i class="fa-brands fa-google-plus"></i></a>
              <a href="#"><i class="fa-brands fa-youtube"></i></a>
          </div>
      
          <div class="footerNav">
              <ul>
                  <li><a href="Abtpage.html">About Us</a></li>
                  <li><a href="FAQ.html">FAQs</a></li>
                  <li><a href="contactpage.html">Contact Us</a></li>
                  <li><a href="#">Track Your Order</a></li>
                  <li><a href="Refund.html">Cancellation and Refund Policy</a></li>
              </ul>
          </div>
      </div>
      <div class="footerBottom">
          <p>Copyright &copy; 2023; Designed by <span class="designer">Giftzz.com</span></p>
      </div>
    </footer>

  </body>

  <script>
    function myFunc() 
        {
            var myButton = document.getElementById("myButton");
            var myDropdown = document.querySelector(".myDropdown");
            if (myDropdown.style.display === "none") 
            {
                myDropdown.style.display = "block";
            } 
            else 
            {
                myDropdown.style.display = "none";
            }
        }
</script>
</html>