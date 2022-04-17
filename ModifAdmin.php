<?php include_once 'dashnav.php'; ?>

<div class="container">
    <h3 class="ct ptitle">Les informations actuelles</h3>
    <ul class="list-group">
        <?php
            if(isset($_GET['modif']))
            {
                $_SESSION['modif'] = $_GET['modif'];
                $q = "SELECT * from clients where id = '{$_GET['modif']}'";
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
        ?>
    </ul>
    <h3 class="ct ptitle">Modification</h3>
            <form method="post" action="ModifAdmin.php" class="logf modf">
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
                <div class="formparts form-group">
                    <label for="inlineFormCustomSelect">Nouveau Role : </label>
                    <select class="custom-select mr-sm-2" name="role" id="inlineFormCustomSelect">
                        <option selected value="N">Changer le role</option>
                        <option value="A">Admin</option>
                        <option value="U">Utilisateur Normale</option>
                    </select>
                </div>
                <button type="submit" class="btn logb">Enregistré</button>
            </form>
            <?php                
                if($_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $done = false;
                    //Put all the returned values from the form in variables
                    $donnee = array(
                        'role' => $_POST['role'],
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
                            header("Location: ModifAdmin.php?error=CIN must be a number&modif={$_SESSION['modif']}");
                            exit();
                        }
                        else
                        {
                            //Check if the cin written is not already exists
                            $i = "SELECT * from clients where id = '{$donnee['id']}'";
                            $resi = mysqli_query($conn,$i);
                            if(mysqli_num_rows($resi) > 0)
                            {
                                header("Location: ModifAdmin.php?error=CIN déja enregistré&modif={$_SESSION['modif']}");
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
                            header("Location: ModifAdmin.php?error=Email déja enregistré&modif={$_SESSION['modif']}");
                            exit();
                        }
                    }

                    foreach($donnee as $kd => $dd)
                    {
                        if(empty($dd) == false)
                        {
                            if(($kd === 'role') && ($dd === 'N'))
                            {
                                continue;
                            }
                            $u = "UPDATE clients set {$kd} = '{$dd}' where  id = '{$_SESSION['modif']}'";
                            mysqli_query($conn,$u);
                            $done = true;
                            if($kd === 'id')
                            {
                                $_SESSION['modif'] = $donnee['id'];
                            }
                        }
                    }
                    if($done == true)
                    {
                        header("Location: ModifAdmin.php?success=&modif={$_SESSION['modif']}");
                    }  
                    else
                    {
                        header("Location: ModifAdmin.php?error=Les champs de modification sont vides&modif={$_SESSION['modif']}");
                    }

                    mysqli_close($conn);
                }
            ?>
</div>

<?php include_once 'layout/footer.php'; ?>