<?php include_once 'dashnav.php'; ?>

<div class="container">
    <div class="row align-items-center">
            <?php

            if (isset($_GET['del'])) 
            {
                $i1 = "SELECT img from voitures where mat = '{$_GET['del']}'";
                $i1d = mysqli_query($conn, $i1);

                if(mysqli_num_rows($i1d) > 0)
                {
                    while($row = mysqli_fetch_assoc($i1d))
                    {
                        $iPath = "image/{$row['img']}";
                        unlink($iPath);
                    }
                }

                $d = "Delete from voitures where mat = '{$_GET['del']}'";
                if (mysqli_query($conn, $d)) {
            ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Voiture Supprimée Avec Succée
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                } else {
                    echo "Failed to delete";
                }
            }

            $q = "SELECT mat, nomV, img, pJ, description from voitures";
            $res = mysqli_query($conn, $q);
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                ?>
                <div class="card col-sm-6 col-md-4 col-xl-3">
                    <div class="card-body" data-toggle="tooltip" data-placement="bottom" title="Prix par jour : <?php echo $row['pJ']; ?>dt">
                        <img class="card-img-top" src="image/<?php echo $row['img']; ?>" alt="<?php echo $row['nomV']; ?>">
                        <h5 class="card-title"><?php echo $row['nomV']; ?></h5>
                        <p><? echo $row['description']; ?></p>
                        <a class="dmc" href="voitures.php?del=<?php echo $row['mat']; ?>"><i class="bi bi-trash"></i></a>
                        <a class="dmc" href="ModifVoiture.php?modif=<?php echo $row['mat']; ?>"><i class="bi bi-pen"></i></a>
                    </div>
                </div>
            <?php
                }
            }

            mysqli_close($conn);
            ?>
    </div>
    <div class="d-flex justify-content-center">
        <a class="aj caraj" href="addcar.php"><i class="bi bi-pen"></i>Ajouter un nouveau voiture</a>
    </div>
</div>
<?php include_once 'layout/footer.php'; ?>