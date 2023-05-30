<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the form values
  $email = $_POST['email'];
  $name = $_POST['itemName'];
  $price = $_POST['price'];

  // Database connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "giftz";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Handle file upload
  $targetDirectory = "userupload/";
  $fileName = $_FILES["itemImage"]["name"];
  $fileTmp = $_FILES["itemImage"]["tmp_name"];
  $targetPath = $targetDirectory . $fileName;

  if (move_uploaded_file($fileTmp, $targetPath)) {
    // File uploaded successfully
    // Prepare and execute the SQL query
    $sql = "INSERT INTO sellnow (email, itemname, itemprice, itemimage) VALUES ('$email', '$name', '$price', '$targetPath')";

    if ($conn->query($sql) === TRUE) {
      echo "<script>window.alert('Item Uploaded'); redirectToPage();</script>";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    // Error in uploading file
    echo "Error uploading file.";
  }

  // Close the database connection
  $conn->close();
}

// Check if the user is logged in
if (isset($_SESSION['email'])) {
  $loggedInEmail = $_SESSION['email'];

  // Database connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "giftz";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query to fetch the items for the logged-in user
  $sql = "SELECT * FROM sellnow WHERE email = '$loggedInEmail'";
  $result = $conn->query($sql);

  // Close the database connection
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Sample</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
   <style>
      @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
      @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@1,300&family=Playfair+Display&display=swap');
      /* Featured products section styles */
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
         margin-left: 1400px;
         justify-content: space-between;
         
      }
      .icons a 
      {
         text-decoration:none;
         color: white;
      }

      .featured-products {
         padding: 2rem;
      }

      .featured-products h2 {
         font-size: 2rem;
         margin-bottom: 2rem;
         text-align: center;
      }

      .product-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(400px,100px));
         gap: 2rem;
      }

      .product-card {
             background-color: #fff;
             width: 300px;
             box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
             text-align: center;
             padding: 2rem;
             border-radius: 2rem;
             transition: all 0.3s ease;
         }

         .product-card:hover {
             transform: translateY(-5px);
         }

         .product-card img {
             max-width: 100%;
             height: auto;
             margin-bottom: 1rem;
         }

         .product-card h3 {
             font-size: 1.5rem;
             margin: 0;
             padding: 0;
             font-family: 'Playfair Display', serif;
         }

         .product-card p.price {
             font-size: 1.2rem;
             margin-top: 0.5rem;
         }
      </style>
   </head>

   <body>
   <header>
   <h1 class="title">Uploaded Items</h1>

   <div class="icons">
      <span class="material-symbols-outlined" style="margin-top: 60px; color:white;"><a href="./SELLNOWFORMPAGE.html">fast_rewind</a></span>
   </div>
   </header>
      <section class="featured-products">
         <div class="product-grid">
            <?php

            if (isset($_SESSION['email'])) {
               $loggedInEmail = $_SESSION['email'];

               // Database connection
               $servername = "localhost";
               $username = "root";
               $password = "";
               $dbname = "giftz";

               $conn = new mysqli($servername, $username, $password, $dbname);

               // Check connection
               if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
               }

               // Prepare and execute the SQL query to fetch the items for the logged-in user
               $sql = "SELECT * FROM sellnow WHERE email = '$loggedInEmail'";
               $result = $conn->query($sql);

               // Display the items
               while ($row = $result->fetch_assoc()) {
                  echo '<div class="product-card">';
                  echo '<img src="' . $row['itemimage'] . '" alt="Item Image">';
                  echo '<h3>' . $row['itemname'] . '</h3>';
                  echo '<p class="price"><span class="WebRupee">Rs.</span>' . $row['itemprice'] . '</p>';
                  echo '</div>';
               }

               // Close the database connection
               $conn->close();
            }
            ?>
         </div>
      </section>
   </body>
</html>

