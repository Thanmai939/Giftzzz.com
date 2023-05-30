<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'giftz';

$con = mysqli_connect($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);

if(mysqli_connect_error())
{
    exit('Error connecting to the database'.mysqli_connect_error());
}

if(!isset($_POST['username'],$_POST['email'],$_POST['password'],$_POST['cpassword'],$_POST['contact'],$_POST['address']))
{
    exit('Empty Field(s)');
}

if(empty($_POST['username'] || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['cpassword']) || empty($_POST['contact']) || empty($_POST['address'])))
{
    exit('Values Empty');
}

if($sql = $con->prepare('SELECT Username,Email,Password,Confirm_Password,Contact,Address FROM users WHERE Username = ?'))
{
    $sql->bind_param('s',$_POST['name']);
    $sql->execute();
    $sql->store_result();
    //Username already exists
    if($sql->num_rows>0)
    {
        echo 'Username already exists. Try Again!';
    }
    else
    {
        if($sql = $con->prepare('INSERT INTO users(Username,Email,Password,Confirm_Password,Contact,Address) VALUES (?,?,?,?,?,?)'))
        {
            $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
            $sql->bind_param('ssssss',$_POST['username'],$_POST['email'],$_POST['password'],$_POST['cpassword'],$_POST['contact'],$_POST['address']);
            $sql->execute();
            //echo "sucessfully registered please login";
            $message = "sucessfully registered please login";
            header('Location:loginregister.html');

        }
        else
        {
            header('Location:loginregister.html');
        }
    }
    $sql->close();
}
else{
    echo 'Error Occurred';
}
$con->close();
?>

<?php