<?php include_once 'dashnav.php'; ?>

<div class="container">
    <ul class="list-group">
        <?php

        if (isset($_GET['del'])) {
            $d = "Delete from clients where id = '{$_GET['del']}'";
            if (mysqli_query($conn, $d)) {
        ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Compte Supprimer Avec Succée
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
            } else {
                echo "Failed to delete";
            }
        }

        $q = "SELECT id, namePren from clients";
        $res = mysqli_query($conn, $q);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
            ?>
                <li class="list-group-item">
                    <?php echo $row['namePren']; ?>
                    <a class="dmc" href="users.php?del=<?php echo $row['id']; ?>"><i class="bi bi-trash"></i></a>
                    <a class="dmc" href="ModifAdmin.php?modif=<?php echo $row['id']; ?>"><i class="bi bi-pen"></i></a>
                </li>
        <?php
            }
        }

        mysqli_close($conn);
        ?>
        <li class="list-group-item d-flex justify-content-center">
            <a class="aj" href="adduser.php"><i class="bi bi-pen"></i>Ajouter un nouveau utilisateur</a>
        </li>
    </ul>
</div>

<?php include_once 'layout/footer.php'; ?>