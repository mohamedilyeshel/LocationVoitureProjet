<?php include_once 'dashnav.php'; ?>

<div class="container">
    <form method="post" action="allreserv.php" class="logf modf">
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Error</h4>
                <p>Désolé, un problème vient de survenir.</p>
                <hr>
                <p class="mb-0"><?php echo $_GET['error']; ?></p>
            </div>
        <?php } ?>
        <div class="formparts form-group ">
            <label for="cin">CIN : </label>
            <input type="text" class="form-control" required name="id" id="cin">
        </div>
        <button type="submit" class="btn logb">Chercher</button>
    </form>
    <?php

    function succ($req, $msg, $conn)
    {
        if (mysqli_query($conn, $req)) {
            ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $msg; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                } 
                else 
                {
                    echo "Failed to confirm the reservation";
                }
    }

    if(isset($_GET['conf']))
    {
        $c = "UPDATE reservation set etat = 'A' where  numRes = '{$_GET['conf']}'";
        succ($c, "Reservation Confirmé", $conn);
    }

    if (isset($_GET['del'])) {
        $d = "Delete from reservation where numRes = '{$_GET['del']}'";
        succ($d, "Reservation Supprimé", $conn);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //Check if the cin written is correct
        if (!(is_numeric($_POST['id']))) {
            header("Location: allreserv.php?error=CIN must be a number");
            exit();
        } else {
            //Check if the cin written is not already exists
            $i = "SELECT * from clients where id = '{$_POST['id']}'";
            $resi = mysqli_query($conn, $i);
            if (mysqli_num_rows($resi) === 0) {
                header("Location: allreserv.php?error=CIN n existe pas");
                exit();
            }
        }

        $idP = $_POST['id'];
        ?>
        <ul class="list-group">
            <?php
            $q = "SELECT * FROM reservation R, voitures V, clients C where R.id = C.id and R.mat = V.mat and R.id = '{$idP}'";
            $res = mysqli_query($conn, $q);

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
            ?>
                    <li class="list-group-item">
                        <?php
                        echo 'Nom Client : ' . $row['namePren'] . ' | Nom Voiture : ' . $row['nomV'] . ' | Date De Prise : ' . $row['dateP'] . ' | Date De Retour : ' . $row['dateR'] . ' | Prix Totale : ' . $row['total'] . 'dt';
                        ?>
                        <a href="allreserv.php?del=<?php echo $row['numRes']; ?>" class="dmc"><i class="bi bi-trash"></i></a>
                        <?php 
                            if($row['etat'] === 'P')
                            {
                        ?>       
                        <a href="allreserv.php?conf=<?php echo $row['numRes'] ?>" class="dmc">Confirmer</a>
                        <?php
                            }
                        ?>
                    </li>
        <?php
                }
            } else {
                echo 'No Result!';
            }
        }

        mysqli_close($conn);
        ?>
        </ul>
</div>
<?php include_once 'layout/footer.php'; ?>