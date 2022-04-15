<?php include_once 'layout/header.php';

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

?>



<?php include_once 'layout/footer.php'; ?>