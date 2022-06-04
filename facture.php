<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/validPanier.css">
    <title>Validation De Votre Panier</title>
</head>
<body>
    <?php
        require_once("./php/manage_panier.php");
        include("./header.php");
        
    ?>
    <div id="align_main_aside">
        <?php
            include("./aside.php");
        ?>
        <main>
            <section id="main_content" style="flex-direction: column" class="main_child"> 
                <?php
                    if(!isset($_SESSION["process_cmd"]) || $_SESSION["process_cmd"] != 'CCO2') { //on vérifie si le processus de commande est respecté
                        unset($_SESSION["process_cmd"]);
                        ?>
                            <h2>Vous êtes sortie ou n'êtes pas dans un processus de commande</h2>
                            <h2>Retournez au panier pour procéder à une commande</h2>
                            <input class="but_link pointer" type="button" value="⇦ Retour au Panier" onclick='location.href="./panier.php"'></input>
                            <input class="but_link pointer" type="button" value="⇦ Retour à l'acceuil" onclick='location.href="./index.php"'></input>
                        <?php 
                    } 
                    else {
                        unset($_SESSION["process_cmd"]);
                        if(!empty($_SESSION['connected']))
                        {
                            $userTab = getUserAllData($_SESSION['connected']);
                        }
                        else
                        {
                            if(isset($_SESSION['connected']))
                            {
                                unset($_SESSION['connected']);
                            }
                            header('Location: ./login.php', true, 301);
                            exit;
                        }
                        if(!empty($_SESSION["correctInputValidpan"]))
                        {
                            $tabCorrectInput = [];
                            foreach($_SESSION['correctInputValidpan'] as $inputName => $input) {
                                $tabCorrectInput[$inputName] = $input;
                            }
                            unset($_SESSION["correctInputValidpan"]);
                        }
                        ?>
                            <h3>Merci pour votre commande !</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th colspan="6">Facture</th>
                                    </tr>
                                    <tr>
                                        <td>Articles</td>
                                        <td>Prix unité</td>
                                        <td>Dont TVA</td>
                                        <td>Quantité</td>
                                        <td>Prix</td>
                                        <td>Dont TVA</td>
                                    </tr>
                                </thead>
                                <?php
                                    setup_fact();
                                    if(isset($_SESSION['panier'])){
                                        panierTransferToHistory();
                                        unset($_SESSION['panier']);
                                        panierTransferFromSession();
                                    }
                                ?>
                            </table>
                            <div class="box_info" id="user_info">
                            <legend><h4>Informations de livraison</h4></legend>
                            <div class="div_input">
                                <div class="input_label">
                                    <label for="address">Adresse de Livraison</label>
                                    <textarea class="adresse input-validpan" name="address" id="address" disabled="disabled" rows="3"><?php if(isset($tabCorrectInput["address"])){echo $tabCorrectInput["address"];}else if(!empty($userTab["fullAddress"]["address"])){echo $userTab["fullAddress"]["address"];}else{echo "";} ?></textarea>
                                </div>
                            </div>
                            <div class="div_input">
                                <div class="input_label">
                                    <label for="postalCode">Code postal</label><br>
                                    <input type="number" name="postalCode" class="input-validpan" id="postalCode"  disabled="disabled" placeholder="Code postal" min="00000" max="99999" class="bouton" value="<?php if(isset($tabCorrectInput["postalCode"])){echo $tabCorrectInput["postalCode"];}else if(!empty($userTab["fullAddress"]["postalCode"])){echo $userTab["fullAddress"]["postalCode"];}else{echo "";} ?>">   
                                </div>
                                <div class="input_label">
                                    <label for="city">Ville</label><br>
                                    <input type="text" name="city" class="input-validpan" id="city"  disabled="disabled" placeholder="Ville" value="<?php if(isset($tabCorrectInput["city"])){echo $tabCorrectInput["city"];}else if(!empty($userTab["fullAddress"]["city"])){echo $userTab["fullAddress"]["city"];}else{echo "";} ?>">
                                </div>
                            </div>
                            <div class="div_input">
                                <div class="input_label">
                                    <label for="name">Nom</label><br>
                                    <input type="text" name="name" class="input-validpan" id="name" disabled="disabled" placeholder="Nom" size="32" value="<?php if(!empty($tabCorrectInput["name"])){echo $tabCorrectInput["name"];}else if(!empty($userTab["name"])){echo trim($userTab["name"]);}else{echo "";} ?>">
                                </div>
                                <div class="input_label">
                                    <label for="firstName">Prénom</label><br>
                                    <input type="text" name="firstName" class="input-validpan" id="firstName" disabled="disabled" placeholder="Prénom" size="32"  value="<?php if(isset($tabCorrectInput["firstName"])){echo $tabCorrectInput["firstName"];}else if(!empty($userTab["firstName"])){echo $userTab["firstName"];}else{echo "";} ?>">
                                </div>
                            </div>

                                <div class="input_label">
                                    <label for="phone">Numéro de téléphone</label><br>
                                    <?php 
                                    if(isset($_SESSION["connected"]))
                                        $phoneNumber=trim(getUserData($_SESSION['connected'], 'phoneNumber'));
                                    else
                                        $phoneNumber= "Not Founded";
                                    ?>
                                    <input type="text" disabled="disabled" name="phoneNumber" id="phoneNumber" disabled="disabled" placeholder="Numéro de téléphone" value="<?=$phoneNumber;?>">
                                </div>
                            
                                <!-- <legend><h4> Nous contacter </h4></legend>

                                <div class="input_label">
                                    <label for="site_address">Adresse du site</label><br>
                                    <?php 
                                        $site_address='Avenue du Parc, Cergy, France';
                                    ?>
                                    <input type="text" disabled="disabled" name="site_address" id="site_address" value="<?=$site_address;?>">
                                </div>

                                <div class="input_label">
                                    <label for="site_email">Email du site</label><br>
                                    <?php 
                                        $site_email = 'contact@librairire.com';
                                    ?>
                                    <input type="text" disabled="disabled" name="site_email" id="site_email" value="<?=$site_email;?>">
                                </div>
                                
                                <div class="input_label">
                                    <label for="site_phone">Numéro de téléphone du site</label><br>
                                    <?php 
                                        $site_phone = '01 55 21 57 93';
                                    ?>
                                    <input type="text" disabled="disabled" name="site_phone" id="site_phone" value="<?=$site_phone;?>">
                                </div> -->
                            </div>
                            <input class="but_link pointer" id="but_botfact" type="button" value="⇦ Retour à l'acceuil" onclick='location.href="./index.php"'></input> 
                        <?php
                    }
                    ?>
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
</body>
</html>