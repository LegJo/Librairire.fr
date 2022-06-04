<?php
    session_start();
    require_once("./manage_panier.php");
    /*processus de deconnexion du user*/
    if(isset($_SESSION['connected'])) //si l'user est connecter
    {   
        panierTransferFromSession(); //stockage du panier dans les fichier
        unset($_SESSION['connected']);
        if(isset($_SESSION['panier']))
        {
            unset($_SESSION['panier']);
        }
    }
    
    header('Location: ../index.php', true, 301);
    exit;
?>