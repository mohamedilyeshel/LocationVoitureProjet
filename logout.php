<?php include_once 'layout/header.php' ?>

<?php 

    session_start();
    session_unset();
    session_destroy();
    
    header('Location: login.php');

    mysqli_close($conn);

?>

<?php include_once 'layout/footer.php' ?>