<?php include_once 'dashnav.php'; ?>

<div class="container">
    <form method="post" action="adduser.php" class="logf modf">
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
                Compte Crée avec succès
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
            <label for="np">Nom & Prenom : </label>
            <input type="text" class="form-control" required name="namePren" id="np">
        </div>
        <div class="formparts form-group ">
            <label for="dn">Date De Naissance : </label>
            <input type="Date" class="form-control" required name="dateNais" id="dn">
        </div>
        <div class="formparts form-group ">
            <label for="email">Email : </label>
            <input type="email" class="form-control" required name="email" id="email">
        </div>
        <div class="formparts form-group">
            <label for="mdp">Mot De Passe : </label>
            <input type="password" class="form-control" required name="mdp" id="mdp">
        </div>
        <div class="formparts form-group">
            <label for="inlineFormCustomSelect">Role : </label>
            <select class="custom-select mr-sm-2" name="role" id="inlineFormCustomSelect">
                <option value="A">Admin</option>
                <option value="U" selected>Utilisateur Normale</option>
            </select>
        </div>
        <button type="submit" class="btn logb">Ajouter</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //Put all the returned values from the form in variables
        $id = $_POST['id'];
        $namePren = $_POST['namePren'];
        $dateNais = $_POST['dateNais'];
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $role = $_POST['role'];

        //Check if the cin written is correct
        if (!(is_numeric($id))) {
            header('Location: adduser.php?error=CIN must be a number');
            exit();
        } else {
            //Check if the cin written is not already exists
            $i = "SELECT * from clients where id = $id";
            $resi = mysqli_query($conn, $i);
            if (mysqli_num_rows($resi) > 0) {
                header('Location: adduser.php?error=CIN déja enregistré');
                exit();
            } else {
                //Check if the email written is not already exists
                $e = "SELECT * from clients where email = '$email'";
                $rese = mysqli_query($conn, $e);
                if (mysqli_num_rows($rese) > 0) {
                    header('Location: adduser.php?error=Email déja enregistré');
                    exit();
                } else {
                    //Insert the user informations in the database
                    $q = "insert into clients (id, namePren, email, mdp, dateNais, role) values ($id, '$namePren', '$email', '$mdp', '$dateNais', '$role')";
                    if (mysqli_query($conn, $q) == 1) {
                        header('Location: adduser.php?success=done');
                    } else {
                        header('Location: adduser.php?error=Problème de serveur Impossible de vous inscrire, essayez plus tard');
                        exit();
                    }
                }
            }
        }
        mysqli_close($conn);
    }
    ?>

</div>

<?php include_once 'layout/footer.php'; ?>