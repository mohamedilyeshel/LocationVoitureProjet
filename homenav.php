<?php include_once 'layout/header.php'; ?>
<div class="container-fluid">
    <div id="titlepart" class="row justify-content-center">
        <h1 class="dptitle ptitle"><?php echo $pName; ?></h1>
    </div>
    <nav class="row align-items-center">
        <ul class="na container-md">
            <li><i class="bi bi-house-door"></i><a href="index.php">Accueil</a></li>
            <li><i class="bi bi-calendar2-range"></i><a href="index.php#hRes">Reservation</a></li>
            <li><i class="bi bi-people"></i><a href="index.php#aprop">A propos</a></li>
            <?php
            session_start();
            if (isset($_SESSION['role'])) {
            ?>
                <li class="dropdown">
                    <i class="bi bi-person"></i>
                    <a class="dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"><? echo $_SESSION['namePren']; ?></a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="paramco.php">Paramétres du compte</a>
                        <?php
                        if ($_SESSION['role'] === "A") {
                        ?>
                            <a class="dropdown-item" href="admindash.php">Admin Dashboard</a>
                        <?php

                        }
                        ?>
                        <a class="dropdown-item" href="mesres.php">Mes reservations</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            <?php
            } else {
            ?>
                <li><i class="bi bi-door-open"></i><a href="login.php">Login</a></li>
            <?php
            }
            ?>
        </ul>
    </nav>
</div>