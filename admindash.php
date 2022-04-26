<?php include_once 'dashnav.php'; 

    $nbU = mysqli_num_rows(mysqli_query($conn,"SELECT id from clients"));
    $nbV = mysqli_num_rows(mysqli_query($conn,"SELECT mat from voitures"));
    $nbR = mysqli_num_rows(mysqli_query($conn,"SELECT numRes from reservation"));
    $totI = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(total) from reservation"));

?>

<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="statBull col-md-6 col-xl-5">
            <div class="statcont">
                <h3 class="ptitle">Nombre d'utilisateur</h3>
                <h5 class="ct display-4"><?php echo $nbU; ?></h5>
            </div>
        </div>
        <div class="statBull col-md-6 col-xl">
            <div class="statcont">
                <h3 class="ptitle">Nombre de voiture</h3>
                <h5 class="ct display-4"><?php echo $nbV; ?></h5>
            </div>
        </div>
        <div class="statBull col-md-6 col-xl-7">
            <div class="statcont">
                <h3 class="ptitle">Nombre de reservation</h3>
                <h5 class="ct display-4"><?php echo $nbR; ?></h5>
            </div>
        </div>
        <div class="statBull col-md-6 col-xl">
            <div class="statcont">
                <h3 class="ptitle">Revenu total</h3>
                <h5 class="ct display-4">
                    <?php                 
                        if($totI['sum(total)'] > 0)
                        {
                            echo $totI['sum(total)']; 
                        }
                        else
                        {
                            echo '0';
                        }
                    ?> DT
                </h5>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>