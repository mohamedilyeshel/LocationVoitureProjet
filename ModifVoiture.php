<?php include_once 'dashnav.php'; ?>

<div class="container">
    <h3 class="ct ptitle">Les informations actuelles</h3>
    <ul class="list-group">
        <?php
        if (isset($_GET['modif'])) {
            $_SESSION['modifvoiture'] = $_GET['modif'];
            $q = "SELECT * from voitures where mat = '{$_GET['modif']}'";
            $res = mysqli_query($conn, $q);
            if (mysqli_num_rows($res) == 1) {
                while ($row = mysqli_fetch_assoc($res)) {
                    foreach ($row as $k => $r) {
                        if ($k != 'img') {
        ?>
                            <li class="list-group-item">
                                <?php

                                switch ($k) {
                                    case 'mat':
                                        echo 'Numero Matricule : ' . $r;
                                        break;
                                    case 'nomV':
                                        echo 'Nom Voiture : ' . $r;
                                        break;
                                    case 'dateA':
                                        echo 'Date D Ajout : ' . $r;
                                        break;
                                    case 'description':
                                        echo 'Description : ' . $r;
                                        break;
                                    case 'pJ':
                                        echo 'Prix Par Jour : ' . $r;
                                        break;
                                }

                                ?>
                            </li>
        <?php
                        }
                    }
                }
            }
        }
        ?>
    </ul>
    <h3 class="ct ptitle">Modification</h3>
          
    <form method="post" action="ModifVoiture.php" class="logf modf" enctype= "multipart/form-data">
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
                Voiture modifié avec succès
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
        <div class="formparts form-group ">
            <label for="mat">Nouveau Matricule : </label>
            <input type="text" class="form-control" name="mat" id="mat">
        </div>
        <div class="formparts form-group ">
            <label for="nomV">Nouveau Nom Voiture : </label>
            <input type="text" class="form-control" name="nomV" id="nomV">
        </div>
        <div class="formparts form-group ">
            <label for="description">Nouveau Description : </label>
            <textarea class="form-control" name="description" id="description"></textarea>
        </div>
        <div class="formparts form-group">
            <label for="pJ">Nouveau Prix Par Jour : </label>
            <input type="number" min="0" class="form-control" name="pJ" id="pJ">
        </div>
        <div class="formparts form-group">
            <label for="img">Nouveau Image : </label>
            <input type="file" name="img" class="form-control-file" id="img">
        </div>
        <button type="submit" class="btn logb">Enregistré</button>
    </form>

        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') 
            {   
                $done = false;
                if($_FILES['img']['size'] > 0)
                {
                    //Put all the image file informations into seperated variables
                    $imgName = $_FILES['img']['name'];
                    $imgSize = $_FILES['img']['size'];
                    $imgTmp = $_FILES['img']['tmp_name'];
                    $imgType = $_FILES['img']['type'];
                    
                    //Precise the allowed file extensions
                    $imgAllowedEx = array("jpeg", "jpg", "png", "gif");

                    //Get the file extension from the file name
                    $imgEx = strtolower(end(explode('.', $imgName)));

                    //Check if the img extension and size are allowed or not
                    if(! in_array($imgEx, $imgAllowedEx))
                    {
                        header('Location: ModifVoiture.php?error=Image Extension not allowed');
                        exit();
                    }

                    if($imgSize > 4194304)
                    {
                        header('Location: ModifVoiture.php?error=Image size is bigger then 4MB');
                        exit();
                    }
                }

                //Put all the returned values from the form in array
                $donnee = array(
                    'pJ' => $_POST['pJ'],
                    'description' => $_POST['description'],
                    'nomV' => $_POST['nomV'],
                    'mat' => $_POST['mat']
                );

                if (empty($donnee['mat']) == false) {
                    //Check if the matricule written is correct
                    if (!(is_numeric($donnee['mat']))) {
                        header("Location: ModifVoiture.php?error=Matricule must be a number&modif={$_SESSION['modifvoiture']}");
                        exit();
                    } else {
                        //Check if the matricule written is not already exists
                        $i = "SELECT * from voitures where mat = '{$donnee['mat']}'";
                        $resi = mysqli_query($conn, $i);
                        if (mysqli_num_rows($resi) > 0) {
                            header("Location: ModifVoiture.php?error=Matricule déja enregistré&modif={$_SESSION['modifvoiture']}");
                            exit();
                        }
                    }
                }

                foreach ($donnee as $kd => $dd) {
                    if (empty($dd) == false) {
                        $u = "UPDATE voitures set {$kd} = '{$dd}' where  mat = '{$_SESSION['modifvoiture']}'";
                        mysqli_query($conn, $u);
                        $done = true;
                        if ($kd === 'mat') {
                            $_SESSION['modifvoiture'] = $donnee['mat'];
                        }
                    }
                }

                if($_FILES['img']['size'] > 0)
                {
                    //Change the img name & upload it on the server
                    $img = rand(0, 1000000). '_' . $imgName;
                    move_uploaded_file($imgTmp, "image\\" . $img);
                    $imgU = "UPDATE voitures set img = '{$img}' where  mat = '{$_SESSION['modifvoiture']}'";
                    mysqli_query($conn, $imgU);
                    $done = true;
                }

                if($done == true)
                {
                    header("Location: ModifVoiture.php?success=&modif={$_SESSION['modifvoiture']}");
                }
                else
                {
                    header("Location: ModifVoiture.php?error=Les champs de modification sont vides&modif={$_SESSION['modifvoiture']}");
                }

                mysqli_close($conn);
            }
        ?>
</div>

    <?php include_once 'layout/footer.php'; ?>