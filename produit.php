<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
        <link type="text/css" rel="stylesheet" href="./css/produit.css">
        <script type="text/javascript" src="./js/script_popup.js"></script>
        <script src=./js/script_produit.js></script>
        <script src=./js/script_ajaxRequest.js></script>

        <title>Librairire</title>
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
                    <?php 
                    $code_prod=getVarUrl("pdt"); // On récupère le code produit dans l'url pour afficher les informations du produit trouvé
                    setup_page_prod($code_prod);
                    ?>
                </section>
                <div id="valid_achat" style="display: none;"> <!-- pop up qui apparait apres un achat validant ou non si l'achat a été transmis au serveur -->
                    <img class="but_close croix" src="./img/croix.png" alt="croix.png" onclick="leave_popup()">
                    <div class="titre">    
                        <h3 id="titre">Et voilà ! C'est ajouté dans votre panier</h3>
                        <p id="resume">Vous avez commandé : Asterix le Gaulois x 5 <br>
                        Le stock n'étant pas assez important nous n'avons pu ajouter à votre panier que :  Asterix le Gaulois x 4
                        </p>                       
                    </div>
                    <div>
                        <input type="button" class="but_link pointer gopan" value="Voir mon panier ⇨" onclick='location.href="./panier.php"'>                        
                    </div>
                </div>
            </main>
        </div>
        <?php
            include("./footer.php");
        ?>
    </body>
</html>
