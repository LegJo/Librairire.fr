<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/panier.css">
    <script src="./js/script_produit.js"></script>
    <script src="./js/script_ajaxRequest.js"></script>
    <title>Panier</title>
</head>
<body>
    <?php 
        session_start();
        require_once("./php/display_obj.php");
        include("./header.php");
    ?>
    <div id="align_main_aside">
        <?php
            include("./aside.php");
        ?>
        <main>
            <section id="main_content" class="main_child"> 
                <div id="panier">
                   <h2>Votre Panier</h2> 
                   <?php
                        setup_pan()
                   ?>
                </div>
            </section>
        </main>
        <div id="pop_cmdconnect" style="display: none; "> <!-- pop up qui apparait si l'user n'est pas connecter au passage de la commande -->
            <img class="but_close" src="./img/croix.png" alt="croix.png" onclick="pop_cccAppear()">
            <div id="pop_cccontent">    
                <h3>Vous devez être connecté pour passer une commande</h3>
                <a href="./login.php" class="pointer but_link">Se Connecter</a>
                <a href="./createAccount.php" class="pointer but_link">S'inscrire</a>
            </div>
        </div>
        <?php
            if(isset($_SESSION['popcmdcon']) && $_SESSION['popcmdcon'])
            {
                unset($_SESSION['popcmdcon']);
                ?><script>pop_cccAppear()</script><?php
            }
        ?>
    </div>
    <?php
        include("./footer.php");
    ?>
</body>
</html>