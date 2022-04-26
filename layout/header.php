<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <?php
    //Based on the page's filename, those codes will change the webpage's title into the correct one
    $pages = array('paramco' => 'Paramétres Du Compte' ,'mesres' => 'Mes Reservations' ,'allreserv' => 'Les Reservations' ,'reservation' => 'Reservation Voiture' ,'ModifVoiture' => 'Modification Voiture' ,'addcar' => 'Ajouter Voiture' ,'voitures' => 'Gestion Voitures' ,'adduser' => 'Ajouter Utilisateur' ,'ModifAdmin' => 'Modification Utilisateur', 'index' => 'Location Voiture', 'login' => 'Login', 'signup' => 'Sign Up', 'admindash' => 'Statistiques', 'users' => 'Gestion Utilisateur');
    foreach ($pages as $path => $name) {
        if (basename($_SERVER['PHP_SELF'], ".php") === $path) {
            $pName = $name;
            break;
        }
    }
    ?>
    <title><?php echo $pName;
            include 'layout/config.php'; ?></title>
</head>

<body>