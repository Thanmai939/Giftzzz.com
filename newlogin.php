<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $email = $_POST["mail"];
    $pwd = $_POST["pwd"];
   
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'giftz';

    $conn = mysqli_connect($host, $username, $password, $dbname);

    if (!$conn) 
    {
        die("Connection Failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM users WHERE Email = ? AND Password = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $pwd);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    if (mysqli_num_rows($res) > 0) 
    {
        $_SESSION["email"] = $email;
    echo '<script>';
    echo 'window.location.href = "videodisplay.html";';
    echo '</script>';
    exit();
    }
    else 
    {
        echo '<script>alert("Username or password is incorrect")</script>';
        // Invalid credentials, redirect back to login page with error message
        //$_SESSION["errorMessage"] = "Invalid Credentials";
        //header('Location: loginregister.html');
        exit();
    }
}
?>