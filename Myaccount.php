

<?php
    // start session
    session_start();
    // check if user is logged in
    if(isset($_SESSION['email']))
    {
        // connect to database
        $conn = mysqli_connect('localhost', 'root', '', 'giftz');
        if(!$conn) 
        {
            die("Connection failed: " . mysqli_connect_error());
        }
        // get user details from database based on email address
        $email = $_SESSION['email'];
        $query = "SELECT * FROM users WHERE Email='$email'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        // Close the database connection
        mysqli_close($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Profile</title>

    <style>
        .page
        {
            height: 747px;
            width: 1535px;
            background-image: linear-gradient(rgba(49, 44, 44, 0.4),rgba(16, 15, 15, 0.4)),url(profilebanner1.jpg);
            /*background-position: center;
            background-image: url(./profilebanner1.jpg);*/
            background-size: cover;
            position: absolute;
            overflow: hidden;
            overflow-y: hidden;
            border: none;
            margin-left: -8px;
            margin-top: -10px;
        }
        p 
        {
            font-size: 40px;
            text-align: center;
            color: rgb(219, 170, 115);
        }
        .box
        {
            width:500px;
            height:400px;
            position:sticky;
            /*margin:2% auto;*/
            margin-left: 350px;
            margin-top: 200px;
            background:rgba(0,0,0,0.3);
            border-radius: 30px;
            padding:10px;
            overflow: hidden;
        }
        .fa-user-circle
        {
            font-size: 100px;
            margin-left: 200px;
            color: #a1693f;
        }
        .emojis .fas
        {
            color: rgb(219, 170, 115);
            font-size: 25px;
            padding: 20px;
        }

        .emojis 
        {
        justify-content: center;
        margin-top: 40px;
        }

        .emojis div {
        display: flex;
        align-items: center;
        margin: 0 20px;
        }

        .emojis div span {
        font-size: 20px;
        color: rgb(216, 181, 141);
        margin-left: 10px;
        }
</style>
</head>
<body>
    <div class="page">
        <div class="box">
            <div>
                <i id="icon" class='fas fa-user-circle' "></i>
            </div>
            <div>
                <p class="name"><?php echo $row["Username"];?></p>
            </div>
            <div class="emojis">
                <div><i id="email" class="fas fa-envelope"></i><span id="mail"><?php echo $_SESSION["email"];?></span></div>
                <div><i id="phoneNo" class="fas fa-phone"></i><span id="ph"><?php echo $row["Contact"];?></span></div>
                <div><i id="address" class="fas fa-map-marker-alt"></i><span id="Ad"><?php echo $row["Address"];?></span></div>
            </div>
        </div> 
    </div> 
</body>
</html>