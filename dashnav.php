<?php include_once 'layout/header.php';
//Don't let any user not an admin access this page
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] !== "A") {
        header('Location: index.php');
    }
} else {
    header('Location: login.php');
}

?>

<div class="container-fluid">
    <div id="titlepart" class="row justify-content-center">
        <h1 class="dptitle ptitle"><?php echo $pName; ?></h1>
    </div>
    <nav class="row align-items-center">
        <ul class="na container-md">
            <li class="col"><i class="bi bi-house-door"></i><a href="index.php">Accueil</a></li>
            <li class="col"><i class="bi bi-clipboard2-data"></i><a href="admindash.php">Statistiques</a></li>
            <li class="col"><i class="bi bi-people"></i><a href="users.php">Utilisateurs</a></li>
            <li class="col"><i class="bi bi-minecart"></i><a href="voitures.php">Voitures</a></li>
            <li class="col"><i class="bi bi-calendar2-range"></i><a href="reservation.php">Reservation</a></li>
            <li class="col"><i class="bi bi-door-closed"></i><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</div>