<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/validPanier.css">
    <script src="./js/script_manage_form.js"></script>
    <title>Validation De Votre Panier</title>
</head>
<body>
    <?php
        session_start();
        include("./header.php");
    ?>
    <div id="align_main_aside">
        <?php
            include("./aside.php");
        ?>
        <main>
            <section id="main_content" class="main_child"> 
                <?php 
                    if(!isset($_SESSION["process_cmd"]) || $_SESSION["process_cmd"] != 'CCO1') { //on vérifie si le processus de commande est respecté
                        unset($_SESSION["process_cmd"]);
                        ?>
                        <div>
                            <h2>Vous êtes sortie ou n'êtes pas dans un processus de commande</h2>
                            <h2>Retournez au panier pour procéder à une commande</h2>
                            <input class="but_link pointer" type="button" value="⇦ Retour au Panier" onclick='location.href="./panier.php"'></input>
                            <input class="but_link pointer" type="button" value="⇦ Retour à l'acceuil" onclick='location.href="./index.php"'></input>
                        </div>
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
            
                    if(!empty($_SESSION['errorMessageValidpan']))
                    {
                        $tabErrorMessage = [];
                        foreach($_SESSION['errorMessageValidpan'] as $errorKey => $error) {
                            $tabErrorMessage[$errorKey] = $error;
                        }
                        unset($_SESSION['errorMessageValidpan']);
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
                        <div class="box_info" id="user_info">
                            <form name="form-validpan" id="form-validpan" action="./php/validpanProcess.php" method='post' onsubmit='return checkingForm("validpan")'>
                                <legend><h4>Informations de livraison</h4></legend>
                                <div class="div_input">
                                    <div class="input_label">
                                        <label for="address">Adresse de Livraison</label>
                                        <?php $classValid = (!empty($tabErrorMessage["address"])) ? 'invalidInput' : '' ?>
                                        <textarea class="adresse input-validpan <?=$classValid?>" name="address" id="address" required rows="3"><?php if(isset($tabCorrectInput["address"])){echo $tabCorrectInput["address"];}else if(!empty($userTab["fullAddress"]["address"])){echo $userTab["fullAddress"]["address"];}else{echo "";} ?></textarea>
                                        <p id="errorMessage-validpan-address" class="errorMessage"><?php if(isset($tabErrorMessage["address"])){echo $tabErrorMessage["address"];}else{echo "";} ?></p>
                                    </div>
                                </div>
                                <div class="div_input">
                                    <div class="input_label">
                                        <label for="postalCode">Code postal</label>
                                        <?php $classValid = (!empty($tabErrorMessage["postalCode"])) ? 'invalidInput' : '' ?>
                                        <input type="number" name="postalCode" class="input-validpan <?=$classValid?>" id="postalCode" required placeholder="Code postal" min="00000" max="99999" class="bouton" value="<?php if(isset($tabCorrectInput["postalCode"])){echo $tabCorrectInput["postalCode"];}else if(!empty($userTab["fullAddress"]["postalCode"])){echo $userTab["fullAddress"]["postalCode"];}else{echo "";} ?>">
                                        <p id="errorMessage-validpan-postalCode" class="errorMessage"><?php if(isset($tabErrorMessage["postalCode"])){echo $tabErrorMessage["postalCode"];}else{echo "";} ?></p> 
                                            
                                    </div>
                                    <div class="input_label">
                                        <label for="city">Ville</label>
                                        <?php $classValid = (!empty($tabErrorMessage["city"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="city" class="input-validpan <?=$classValid?>" id="city" required placeholder="Ville" value="<?php if(isset($tabCorrectInput["city"])){echo $tabCorrectInput["city"];}else if(!empty($userTab["fullAddress"]["city"])){echo $userTab["fullAddress"]["city"];}else{echo "";} ?>">
                                        <p id="errorMessage-validpan-city" class="errorMessage"><?php if(isset($tabErrorMessage["city"])){echo $tabErrorMessage["city"];}else{echo "";} ?></p>
                                    </div>
                                </div>
                                <div class="div_input">
                                    <div class="input_label">
                                        <label for="name">Nom</label>
                                        <?php $classValid = (!empty($tabErrorMessage["name"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="name" class="input-validpan <?=$classValid?>" id="name" required placeholder="Nom" size="32" value="<?php if(!empty($tabCorrectInput["name"])){echo $tabCorrectInput["name"];}else if(!empty($userTab["name"])){echo $userTab["name"];}else{echo "";} ?>">
                                        <p id="errorMessage-validpan-name" class="errorMessage"><?php if(isset($tabErrorMessage["name"])){echo $tabErrorMessage["name"];}else{echo "";} ?></p> <!-- on indente pas psk c'est la meme chose tout le temps -->
                                    </div>
                                    <div class="input_label">
                                        <label for="firstName">Prénom</label>
                                        <?php $classValid = (!empty($tabErrorMessage["firstName"])) ? 'invalidInput' : '' ?>
                                        <input type="text" name="firstName" class="input-validpan <?=$classValid?>" id="firstName" required placeholder="Prénom" size="32"  value="<?php if(isset($tabCorrectInput["firstName"])){echo $tabCorrectInput["firstName"];}else if(!empty($userTab["firstName"])){echo $userTab["firstName"];}else{echo "";} ?>">
                                        <p id="errorMessage-validpan-firstName" class="errorMessage"><?php if(isset($tabErrorMessage["firstName"])){echo $tabErrorMessage["firstName"];}else{echo "";} ?></p>
                                    </div>
                                </div>
                                <input type="submit" name="submitValidpan" value="Procéder au paiement" class="pointer but_link">  
                            </form>
                        </div>

                                <table>
                                    <thead>
                                        <tr>
                                            <th colspan="6">Résumé de votre commande</th>
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
                                    ?>
                                </table> 
                                                    
                                <!-- <a href="./php/commandProcess.php?pro=2" class="pointer but_link">Procéder au paiement</a> -->
                            
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