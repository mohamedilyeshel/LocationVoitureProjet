<?php include_once 'homenav.php';
//Don't let any user not an admin access this page
if (! (isset($_SESSION['role'])) ) 
{
    header('Location: login.php');
    exit();
}
?>
<div class="container">
<?php
$idP = $_SESSION['id'];
?>
<ul class="list-group">
    <?php

    if (isset($_GET['del'])) {
        $d = "Delete from reservation where numRes = '{$_GET['del']}'";
        if (mysqli_query($conn, $d)) {
    ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Reservation Supprimée Avec Succée
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } else {
            echo "Failed to delete";
        }
    }
    ?>
    <?php
    $q = "SELECT * FROM reservation R, voitures V, clients C where R.id = C.id and R.mat = V.mat and R.id = '{$idP}'";
    $res = mysqli_query($conn, $q);

    if (mysqli_num_rows($res) > 0) 
    {
        while ($row = mysqli_fetch_assoc($res)) 
        {
    ?>
            <li class="list-group-item">
                <?php
                echo 'Nom Client : ' . $row['namePren'] . ' | Nom Voiture : ' . $row['nomV'] . ' | Date De Prise : ' . $row['dateP'] . ' | Date De Retour : ' . $row['dateR'] . ' | Prix Totale : ' . $row['total'] . 'dt';
                ?>
                <a href="mesres.php?del=<?php echo $row['numRes']; ?>" class="dmc"><i class="bi bi-trash"></i></a>
            </li>
<?php
        }
    } 
    else 
    {
        echo 'No Result!';
    }

mysqli_close($conn);
?>
</ul>
</div>
<?php include_once 'layout/footer.php'; ?>