<?php include_once 'homenav.php'; ?>
<div class="container">
    <div id="lastVoit" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
            <?php

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if ((isset($_GET['tot'])) && (isset($_GET['matr']))) {
                    //Insert the reservation informations in the database
                    $ins = "INSERT into reservation (id, mat, dateP, dateR, etat, total) values ('{$_SESSION['idPR']}', '{$_GET['matr']}', '{$_SESSION['dateP']}', '{$_SESSION['dateR']}', 'P', '{$_GET['tot']}')";
                    if (mysqli_query($conn, $ins) == 1) {
                        header('Location: index.php?success=');
                        unset($_GET);
                        exit();
                    } else {
                        header('Location: index.php?error=Erreur Connection, Essaie une autre fois');
                        unset($_GET);
                        exit();
                    }
                }
            }

            $currentDate = date('Y-m-d');
            $gTogo = false;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_SESSION['role']) == False) {
                    header("Location: login.php");
                    exit();
                }

                if ($_POST['dateP'] < $currentDate) {
                    header("Location: index.php?error=Date de prise doit etre ajourdhui ou apres");
                    exit();
                }

                if ($_POST['dateP'] <= $_POST['dateR']) {
                    if (empty($_POST['dateR']) == false) {
                        $_SESSION['idPR'] = $_SESSION['id'];
                        $_SESSION['dateP'] = $_POST['dateP'];
                        $_SESSION['dateR'] = $_POST['dateR'];
                    }
                } else {
                    header("Location: index.php?error=Date de prise doit etre avant la date de retour");
                    exit();
                }

                $gTogo = true;
            }

            $q = "SELECT * from voitures";
            $res = mysqli_query($conn, $q);
            $i = 0;
            if (mysqli_num_rows($res) > 0) {
                while (($row = mysqli_fetch_assoc($res)) && ($i < 9)) {
                    $i += 1;
                    if ($i === 1) {
            ?>
                        <div class="carousel-item active">
                            <img src="image/<?php echo $row['img']; ?>" class="d-block w-100" alt="<?php echo $row['nomV']; ?>" style="height: 600px;">
                            <div class="carousel-caption d-none d-md-block">
                                <h5><?php echo $row['nomV']; ?></h5>
                                <p><?php echo $row['description']; ?></p>
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="carousel-item">
                            <img src="image/<?php echo $row['img']; ?>" class="d-block w-100" alt="<?php echo $row['nomV']; ?>" style="height: 600px;">
                            <div class="carousel-caption d-none d-md-block">
                                <h5><?php echo $row['nomV']; ?></h5>
                                <p><?php echo $row['description']; ?></p>
                            </div>
                        </div>
            <?php
                    }
                }
            } else {
                echo "No result";
            }
            ?>
        </div>
        <a class="carousel-control-prev" href="#lastVoit" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#lastVoit" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

<div class="container-fluid" id="hRes">
    <div class="container">
        <form method="post" action="index.php" class="logf modf">
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
                    Reservation enregistré avec succès. Pour confirmer la reservation, il faut payer le montant a l'agence.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
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

            if ($gTogo == true) {
                $alShown = array();

                if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM reservation")) == 0) {
                    unset($res1);
                    $q1 = "SELECT mat, nomV, pJ from voitures";
                    $res1 = mysqli_query($conn, $q1);
                    $alShown = reserv($res1, $alShown);
                } else {
                    $q2 = array(
                        "SELECT V.mat, V.nomV, V.pJ from voitures V, reservation R 
                            where (V.mat = R.mat) and ( (('{$_SESSION['dateP']}' < R.dateP) and ('{$_SESSION['dateR']}' < R.dateP)) or (('{$_SESSION['dateP']}' > R.dateR) and ('{$_SESSION['dateR']}' > R.dateR)) )",
                        "SELECT mat, nomV, pJ from voitures where mat not in (select mat from reservation)"
                    );

                    foreach ($q2 as $qu) {
                        unset($res1);
                        $res1 = mysqli_query($conn, $qu);
                        $alShown = reserv($res1, $alShown);
                    }
                }
            }

            function reserv($rese, $alShown)
            {
                if (mysqli_num_rows($rese) > 0) {
                    while ($row = mysqli_fetch_assoc($rese)) {
                        if (in_array($row['mat'], $alShown) == false) {
                            $alShown[] = $row['mat'];
            ?>
                            <li class="list-group-item">
                                <?php
                                $dN = intval(date('d', strtotime($_SESSION['dateR']) - strtotime($_SESSION['dateP'])));
                                $tot = $row['pJ'] * $dN;
                                echo 'Nom Voiture : ' . $row['nomV'] . ' | Prix Par Jour : ' . $row['pJ'] . 'dt | Prix Totale : ' . $tot . 'dt';
                                ?>
                                <a href="index.php?tot=<?php echo $tot; ?>&matr=<?php echo $row['mat'] ?>" class="dmc"><i class="bi bi-calendar2-range"></i>Reserver</a>
                            </li>
            <?php
                        }
                    }
                }

                return $alShown;
            }

            mysqli_close($conn);
            ?>
        </ul>
    </div>
</div>

<div class="jumbotron jumbotron-fluid aprop" id="aprop">
    <div class="container">
        <h1 class="display-4">A Propos</h1>
        <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur cupiditate voluptates vitae reiciendis blanditiis odio perferendis! Eligendi, natus maxime explicabo modi impedit veritatis in. Amet perspiciatis unde eveniet repellat eligendi!
            Placeat possimus veniam corrupti facere sapiente, iure dolor harum ut inventore, cumque tenetur, neque delectus dicta hic maiores suscipit asperiores eaque culpa maxime nulla illo! Neque eius dolores officia accusamus.
            Iste, eum fugit! Minus esse totam provident assumenda adipisci odit, excepturi cupiditate fugit dignissimos modi. Debitis quam obcaecati iusto nam necessitatibus facilis, voluptatum vitae quisquam laborum dicta exercitationem in fugiat.
            Aperiam sint rem accusantium! Esse saepe perspiciatis ab obcaecati magni rerum impedit voluptatem aperiam aut illum quibusdam alias id, eum vel explicabo fuga nostrum asperiores eveniet odit ad quam ratione!</p>
    </div>
</div>

<?php include_once 'layout/footer.php';?>