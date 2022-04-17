<?php include_once 'dashnav.php'; ?>

<div class="container">
    <form method="post" action="addcar.php" class="logf modf" enctype= "multipart/form-data">
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
                Voiture ajoutée avec succès
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
        <div class="formparts form-group ">
            <label for="mat">Matricule : </label>
            <input type="text" class="form-control" required name="mat" id="mat">
        </div>
        <div class="formparts form-group ">
            <label for="nomV">Nom Voiture : </label>
            <input type="text" class="form-control" required name="nomV" id="nomV">
        </div>
        <div class="formparts form-group ">
            <label for="description">Description : </label>
            <textarea class="form-control" name="description" required id="description"></textarea>
        </div>
        <div class="formparts form-group">
            <label for="pJ">Prix Par Jour : </label>
            <input type="number" min="0" class="form-control" required name="pJ" id="pJ">
        </div>
        <div class="formparts form-group">
            <label for="img">Image : </label>
            <input type="file" name="img" required class="form-control-file" id="img">
        </div>
        <button type="submit" class="btn logb">Ajouter</button>
    </form>

    <?php
    $currentDate = date('y-m-d');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        //Put all the returned values from the form in variables
        $mat = $_POST['mat'];
        $nomV = $_POST['nomV'];
        $dateA = $currentDate;
        $description = $_POST['description'];
        $pJ = $_POST['pJ'];

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
            header('Location: addcar.php?error=Image Extension not allowed');
            exit();
        }

        if($imgSize > 4194304)
        {
            header('Location: addcar.php?error=Image size is bigger then 4MB');
            exit();
        }

        //Check if the matricule written is correct
        if (!(is_numeric($mat))) 
        {
            header('Location: addcar.php?error=Matricule must be a number');
            exit();
        } 
        else 
        {
            //Check if the matricule written is not already exists
            $i = "SELECT * from voitures where mat = $mat";
            $resi = mysqli_query($conn, $i);
            if (mysqli_num_rows($resi) > 0) 
            {
                header('Location: addcar.php?error=Matricule déja enregistré');
                exit();
            } 
            else 
            {
                //Change the img name & upload it on the server
                $img = rand(0, 1000000). '_' . $imgName;
                move_uploaded_file($imgTmp, "image\\" . $img);

                //Insert the car informations in the database
                $q = "insert into voitures (mat, nomV, dateA, description, img, pJ) values ($mat, '$nomV', '$dateA', '$description', '$img', '$pJ')";
                if (mysqli_query($conn, $q))
                {
                    header('Location: addcar.php?success=done');
                    exit();
                } 
                else 
                {
                    header('Location: addcar.php?error=Problème de serveur Impossible de vous inscrire, essayez plus tard');
                    exit();
                }
            }
        }
        mysqli_close($conn);
    }
    ?>
</div>


<?php include_once 'layout/footer.php'; ?>