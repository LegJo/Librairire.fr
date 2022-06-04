<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/aPropos.css">
    <title>Notre entreprise</title>
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
                <div id="main_aPropos">
                    <h2>Notre équipe :</h2>
                    <div id="Workers">
                        <div class="Worker">
                            <img id="logo_h" class="logo_L" src="./img/Logo_Librairire.png" alt="logo">
                            BABIKIAN Mathieu
                        </div>
                        <div class="Worker">
                            <img id="logo_h" class="logo_L" src="./img/Logo_Librairire.png" alt="logo">
                            BOUHRARA Adam
                        </div>
                        <div class="Worker">
                            <img id="logo_h" class="logo_L" src="./img/Logo_Librairire.png" alt="logo">
                            GAUTIER Jordan
                        </div>
                        <div class="Worker">
                            <img id="logo_h" class="logo_L" src="./img/Logo_Librairire.png" alt="logo">
                            LEGRAND Joan
                        </div>
                        <div class="Worker">
                            <img id="logo_h" class="logo_L" src="./img/Logo_Librairire.png" alt="logo">
                            VELAY Lucas
                        </div>
                    </div>
                    <div id="Mention_et_infos">
                        <div class="box_info" id="Mention_legale">
                            <h2>Mention légales :</h2>
                            <p>Librairire affiche les prix de chaque article. Les frais de livraisons sont offerts pour toutes commandes. La TVA française s'applique sur chacun de nos articles.</p>
                            <p>Le service << Marketplace >> de Librairire se réserve le droit d'annuler toute commande si elle ne répond pas au critère régit par la loi française.</p>
                            <p>Si vous avez d'autres questions, <a href="./contact.php">Contactez-nous</a></p>
                        </div>
                        <div class="box_info" id="Infos">
                            <h2>Informations supplémentaires :</h2>
                            <p>Vous pouvez nous retrouver sur nos réseaux sociaux (<a>Instagram</a>, <a>Twitter</a>)</p>
                            <ul>
                                <li><p>Adresse : Avenue du Parc, Cergy, France</p></li>
                                <li><p>Email : contact@librairire.com</p></li>
                                <li><p>Tél : 01 55 21 57 93</p></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
</body>
</html>