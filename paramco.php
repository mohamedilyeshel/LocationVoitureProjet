<?php include_once 'homenav.php';
//Don't let any user not an admin access this page
if (! (isset($_SESSION['role'])) ) 
{
    header('Location: login.php');
    exit();
}
?>

<?php                
                if($_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $done = false;
                    //Put all the returned values from the form in variables
                    $donnee = array(
                        'dateNais' => $_POST['dateNais'], 
                        'email' => $_POST['email'], 
                        'mdp' => $_POST['mdp'], 
                        'namePren' => $_POST['namePren'],                       
                        'id' => $_POST['id']
                    );

                    if(empty($donnee['id']) == false)
                    {
                        //Check if the cin written is correct
                        if(!(is_numeric($donnee['id'])))
                        {
                            header("Location: paramco.php?error=CIN must be a number");
                            exit();
                        }
                        else
                        {
                            //Check if the cin written is not already exists
                            $i = "SELECT * from clients where id = '{$donnee['id']}'";
                            $resi = mysqli_query($conn,$i);
                            if(mysqli_num_rows($resi) > 0)
                            {
                                header("Location: paramco.php?error=CIN déja enregistré");
                                exit();
                            }
                        }
                    }

                    if(empty($donnee['email']) == false)
                    {
                        //Check if the email written is not already exists
                        $e = "SELECT * from clients where email = '{$donnee['email']}'";
                        $rese = mysqli_query($conn,$e);
                        if(mysqli_num_rows($rese) > 0)
                        {
                            header("Location: paramco.php?error=Email déja enregistré");
                            exit();
                        }
                    }

                    foreach($donnee as $kd => $dd)
                    {
                        if(empty($dd) == false)
                        {
                            $u = "UPDATE clients set {$kd} = '{$dd}' where  id = '{$_SESSION['id']}'";
                            mysqli_query($conn,$u);
                            $done = true;
                            if($kd === 'id')
                            {
                                $_SESSION['id'] = $donnee['id'];
                            }
                        }
                    }

                    if($done == true)
                    {
                        header("Location: paramco.php?success=");
                        exit();
                    }  
                    else
                    {
                        header("Location: paramco.php?error=Les champs de modification sont vides");
                        exit();
                    }
                }
                    
                if (isset($_GET['del'])) 
                {
                    $d = "Delete from clients where id = '{$_GET['del']}'";
                    if (mysqli_query($conn, $d)) {
                ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Compte Supprimée Avec Succée
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                    header("Location: logout.php");
                    exit();
                    } 
                    else 
                    {
                        echo "Failed to delete";
                    }
                }

            ?>

<div class="container">
    <h3 class="ct ptitle">Les informations actuelles</h3>
    <ul class="list-group">
        <?php
            if(isset($_SESSION['id']))
            {
                $q = "SELECT * from clients where id = '{$_SESSION['id']}'";
                $res = mysqli_query($conn, $q);
                if (mysqli_num_rows($res) == 1) 
                {
                    while ($row = mysqli_fetch_assoc($res)) 
                    {
                        foreach($row as $k => $r)
                        {
                            ?>
                            <li class="list-group-item">
                                <?php 
                                
                                    switch($k)
                                    {
                                        case 'id':
                                            echo 'Numero CIN : ' .$r;
                                            break;
                                        case 'namePren':
                                            echo 'Nom et Prenom : ' .$r;
                                            break;
                                        case 'email':
                                            echo 'Email : ' .$r;
                                            break;
                                        case 'mdp':
                                            echo 'Mot De Passe : ' .$r;
                                            break;
                                        case 'dateNais':
                                            echo 'Date De Naissance : ' .$r;
                                            break;
                                        case 'role':
                                            echo 'Role (U : User Normal | A : Admin) : ' .$r;
                                            break;
                                    }
    
                                ?>
                            </li>
                    <?php
                        }
                    }
                }
            }
            mysqli_close($conn);
        ?>
    </ul>
    <h3 class="ct ptitle">Modification</h3>
            <form method="post" action="paramco.php" class="logf modf">
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
                    Compte modifié avec succès
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                <div class="formparts form-group ">
                    <label for="cin">Nouveau CIN : </label>
                    <input type="text" class="form-control" name="id" id="cin">
                </div>
                <div class="formparts form-group ">
                    <label for="np">Nouveau Nom & Prenom : </label>
                    <input type="text" class="form-control" name="namePren" id="np">
                </div>
                <div class="formparts form-group ">
                    <label for="dn">Nouveau Date De Naissance : </label>
                    <input type="Date" class="form-control" name="dateNais" id="dn">
                </div>
                <div class="formparts form-group ">
                    <label for="email">Nouveau Email : </label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
                <div class="formparts form-group">
                    <label for="mdp">Nouveau Mot De Passe : </label>
                    <input type="password" class="form-control" name="mdp" id="mdp">
                </div>
                <button type="submit" class="btn logb">Enregistré</button>
            </form>    
    <h3 class="ct ptitle">Supprimer Le Compte</h3>
    <a class="aj supp" href="paramco.php?del=<?php echo $_SESSION['id']; ?>"><i class="bi bi-trash"></i>Supprimer le compte</a>
</div>

<?php include_once 'layout/footer.php'; ?>