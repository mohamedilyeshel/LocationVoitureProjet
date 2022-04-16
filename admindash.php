<?php include_once 'layout/header.php';
    //Don't let any user not an admin access this page
    session_start();
    if(isset($_SESSION['role']))
    {
        if($_SESSION['role'] !== "A")
        {
            header('Location: index.php');
        }
    }
    else
    {
        header('Location: login.php');
    }

    mysqli_close($conn);

?>

<?php include_once 'layout/footer.php'; ?>