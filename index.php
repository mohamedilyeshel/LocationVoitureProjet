<?php include_once 'layout/header.php'; ?>

<?php
session_start();

if (isset($_SESSION['namePren'])) 
{
    if ($_SESSION['role'] == 'A') 
    {
        echo "Hello {$_SESSION['namePren']} you are the admin! you can access your dashboard from here";
?>
        <a href="admindash.php"> Admin Dashboard</a>
    <?php
    } 
    else 
    {
        echo "Hello {$_SESSION['namePren']} enjoy your stay here!";
    }
    ?>
    <a href="logout.php"> | Log Out</a>
<?php
} else {
    echo "You have to log in";
}

mysqli_close($conn);
?>

<?php include_once 'layout/footer.php'; ?>