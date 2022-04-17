<?php include_once 'layout/header.php' ?>

<?php 
    //Delete every value in the session array then destroy it and relocate the user to the login page (Logout concepte)
    session_start();
    session_unset();
    session_destroy();
    
    header('Location: login.php');
    exit();
    mysqli_close($conn);

?>

<?php include_once 'layout/footer.php' ?>