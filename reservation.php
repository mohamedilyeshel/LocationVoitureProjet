<?php include_once 'dashnav.php'; ?>

<div class="container">
    <form method="post" action="reservation.php" class="logf modf">
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Error</h4>
                <p>Désolé, un problème vient de survenir.</p>
                <hr>
                <p class="mb-0"><?php echo $_GET['error']; ?></p>
            </div>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success  alert-dismissible fade show" role="alert">
                Reservation enregistré et confirmé avec succès
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
        <div class="formparts form-group ">
            <label for="cin">CIN : </label>
            <input type="text" class="form-control" required name="id" id="cin">
        </div>
        <div class="formparts form-group ">
            <label for="dateP">Date De Prise : </label>
            <input type="Date" class="form-control" required name="dateP" id="dateP">
        </div>
        <div class="formparts form-group ">
            <label for="dateR">Date De Retour : </label>
            <input type="Date" class="form-control" required name="dateR" id="dateR">
        </div>
        <button type="submit" class="btn logb">Reserver</button>
    </form>
    <ul class="list-group">
        <?php

            if($_SERVER['REQUEST_METHOD'] === 'GET')
            {
                if((isset($_GET['tot']))&&(isset($_GET['matr'])))
                {
                    //Insert the reservation informations in the database
                    $ins = "INSERT into reservation (id, mat, dateP, dateR, etat, total) values ('{$_SESSION['idPR']}', '{$_GET['matr']}', '{$_SESSION['dateP']}', '{$_SESSION['dateR']}', 'A', '{$_GET['tot']}')";
                    if (mysqli_query($conn, $ins) == 1) 
                    {
                        header('Location: reservation.php?success=');
                        unset($_GET);
                        exit();
                    }
                    else
                    {
                        header('Location: reservation.php?error=Erreur Connection, Essaie une autre fois');
                        unset($_GET);
                        exit();
                    }
                }
            }

            $currentDate = date('Y-m-d');
            if($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                if($_POST['dateP'] < $currentDate)
                {
                    header("Location: reservation.php?error=Date de prise doit etre ajourdhui ou apres");
                    exit();
                }

                if($_POST['dateP'] <= $_POST['dateR'])
                {
                    if(empty($_POST['dateR']) == false)
                    {
                        //Check if the cin written is correct
                        if(!(is_numeric($_POST['id'])))
                        {
                            header("Location: reservation.php?error=CIN must be a number");
                            exit();
                        }
                        else
                        {
                            //Check if the cin written is not already exists
                            $i = "SELECT * from clients where id = '{$_POST['id']}'";
                            $resi = mysqli_query($conn,$i);
                            if(mysqli_num_rows($resi) === 0)
                            {
                                header("Location: reservation.php?error=CIN n est pas enregistré");
                                exit();
                            }
                        }
                        $_SESSION['idPR'] = $_POST['id'];
                        $_SESSION['dateP'] = $_POST['dateP'];
                        $_SESSION['dateR'] = $_POST['dateR'];
                    }
                }
                else
                {
                    header("Location: reservation.php?error=Date de prise doit etre avant la date de retour");
                    exit();
                }

                function reserv($rese, $alShown)
                {
                    if (mysqli_num_rows($rese) > 0) 
                    {
                        while ($row = mysqli_fetch_assoc($rese)) 
                        {  
                            if(in_array($row['mat'], $alShown) == false)
                            {
                                $alShown[] = $row['mat'];
                    ?>
                            <li class="list-group-item">
                                <?php 
                                    $dN = intval(date('d',strtotime($_SESSION['dateR']) - strtotime($_SESSION['dateP'])));
                                    $tot = $row['pJ'] * $dN;
                                    echo 'Nom Voiture : '.$row['nomV'].' | Prix Par Jour : '.$row['pJ']. 'dt | Prix Totale : '.$tot.'dt';                 
                                ?>
                                <a href="reservation.php?tot=<?php echo $tot; ?>&matr=<?php echo $row['mat'] ?>" class="dmc"><i class="bi bi-calendar2-range"></i>Reserver</a>
                            </li>
                    <?php 
                            }                      
                        }
                    }

                    return $alShown;
                }

                $alShown = array();

                if(mysqli_num_rows(mysqli_query($conn,"SELECT * FROM reservation")) == 0)
                {
                    unset($res);
                    $q1 = "SELECT mat, nomV, pJ from voitures";
                    $res = mysqli_query($conn, $q1);
                    $alShown = reserv($res, $alShown);         
                }
                else
                {
                    $q = array(
                        "SELECT V.mat, V.nomV, V.pJ from voitures V, reservation R 
                        where (V.mat = R.mat) and ( (('{$_SESSION['dateP']}' < R.dateP) and ('{$_SESSION['dateR']}' < R.dateP)) or (('{$_SESSION['dateP']}' > R.dateR) and ('{$_SESSION['dateR']}' > R.dateR)) )",
                        "SELECT mat, nomV, pJ from voitures where mat not in (select mat from reservation)"
                    );

                    foreach($q as $qu)
                    {
                        unset($res);
                        $res = mysqli_query($conn, $qu);
                        $alShown = reserv($res, $alShown);
                    }
                }
            }

            mysqli_close($conn);
        ?>
        <li class="list-group-item d-flex justify-content-center">
            <a href="allreserv.php" class="aj"><i class="bi bi-calendar2-range"></i>Afficher les reservations</a>
        </li>
    </ul>
</div>

<?php include_once 'layout/footer.php'; ?>