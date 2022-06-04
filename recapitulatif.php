<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/contact.css">
    <title>Votre demande</title>
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
                <div id="Demande">
                    <h1>Votre demande : <?php echo $_GET["sujet"] ?></h1>
                    <div class="align_case">
                        <p class="case_recap">Nom : <?php echo $_GET["nom"] ?></p>
                        <p class="case_recap">Prénom : <?php echo $_GET["prenom"] ?></p>
                        <p class="case_recap">Sexe : <?php echo $_GET["sexe"] ?></p>
                    </div>
                    <div id="case_recap">
                        <p>On vous recontactera le <strong><?php echo date_format(new DateTime($_GET["date_de_contact"]), 'd/m/Y') ?></strong> sur votre adresse <strong><?php echo $_GET["email"] ?></strong> pour le message :</p>
                    </div>
                    <div class="align_case">
                        <p id="case_message"><?php echo $_GET["message"] ?></p>
                    </div>
                </div>
                <a id="retour_menu" class="but_link" href="./index.php">Retour a l'accueil</a>
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
</body>
</html>