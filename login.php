<?php include_once 'layout/header.php';

    session_start();
    if(isset($_SESSION['namePren']))
    {
        header('Location: index.php');
    }

?>

    <div class="container-fluid">
        <div class="row fullheight justify-content-center align-items-center">
            <form method="post" action="login.php" class="col-sm-6 col-md-5 col-lg-4 col-xl-3 logf">
                <p class="ptitle">Log In</p>
                <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Error</h4>
                    <p>Désolé, un problème vient de survenir.</p>
                    <hr>
                    <p class="mb-0"><?php echo $_GET['error']; ?></p> 
                </div>
                <?php } ?>
                <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Compte créé avec succès</h4>
                    <p>Vous pouvez connecter à votre compte maintenant!</p>
                </div>
                <?php } ?>
                <div class="formparts form-group ">
                    <label for="email">Email : </label>
                    <input type="email" class="form-control" required name="email" id="email">
                </div>
                <div class="formparts form-group">
                    <label for="mdp">Mot De Passe : </label>
                    <input type="password" class="form-control" required name="mdp" id="mdp">
                </div>
                <button type="submit" class="btn logb">Log In</button>
                <hr class="fhr">
                <a href="signup.php" class="signul">Crée un compte</a>
            </form>
            <?php
                if(isset($_POST['email']) && isset($_POST['mdp']))
                {
                    session_start();
                    $mdp = $_POST['mdp'];
                    $email = $_POST['email'];
                    $q = "SELECT * from clients where email = '$email' && mdp = '$mdp'";
                    $res = mysqli_query($conn,$q);
                    if(mysqli_num_rows($res) == 1)
                    {
                        while($row = mysqli_fetch_assoc($res))
                        {
                            $_SESSION['id'] = $row['id'];
                            $_SESSION['namePren'] = $row['namePren'];
                            $_SESSION['email'] = $row['email'];
                            $_SESSION['mdp'] = $row['mdp'];
                            $_SESSION['dateNais'] = $row['dateNais'];
                            $_SESSION['role'] = $row['role'];
                            header('Location: index.php');
                        }
                    }
                    else
                    {
                        header('Location: login.php?error=Email ou le mdp est incorrect');
                    }

                    mysqli_close($conn);
                }
            ?>
        </div>
    </div>

    <?php include_once 'layout/footer.php' ?>