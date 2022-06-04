<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/Logo_Librairire.png">
    <link type="text/css" rel="stylesheet" href="./css/contact.css">
    <title>Contactez-nous</title>
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

                if(!empty($_SESSION['connected']))
                {
                    $userTab = getUserAllData($_SESSION['connected']);
                }


                if(!empty($_SESSION['errorMessageContact']))
                {
                    $tabErrorMessage = [];
                    foreach($_SESSION['errorMessageContact'] as $errorKey => $error) {
                        $tabErrorMessage[$errorKey] = $error;
                    }
                    unset($_SESSION['errorMessageContact']);
                }

                if(!empty($_SESSION["correctInputContact"]))
                {
                    $tabCorrectInput = [];
                    foreach($_SESSION['correctInputContact'] as $inputName => $input) {
                        $tabCorrectInput[$inputName] = $input;
                    }
                    unset($_SESSION["correctInputContact"]);
                }
                ?>

                
                <!--Formulaire de contact-->

                <form name="form-contact" method="post" action="./php/contactProcess.php" onsubmit="return checkingForm('contact')">
                    <div id="formulaire">
                        <h1>Demande de contact</h1>
                        <div id="Sujet_select">
                            <label for="topic">Sujet :</label>
                            <?php $classValid = (!empty($tabErrorMessage["topic"])) ? 'invalidInput' : '' ?>
                            <select name="topic" id="Sujet" class="pointer <?=$classValid?>" required>
                                <option value="">-Choix du sujet-</option>
                                <option value="Remboursement" <?php if(isset($tabCorrectInput["topic"]) && $tabCorrectInput["topic"] === "Remboursement"){echo "selected";}else{echo "";} ?>>Remboursement</option>
                                <option value="Livraison" <?php if(isset($tabCorrectInput["topic"]) && $tabCorrectInput["topic"] === "Livraison"){echo "selected";}else{echo "";}?>>Livraison</option>
                                <option value="Autre" <?php if(isset($tabCorrectInput["topic"]) && $tabCorrectInput["topic"] === "Autre"){echo "selected";}else{echo "";}?>>Autre</option>
                            </select> 
                            <p id="errorMessage-contact-topic" class="errorMessage"><?php if(isset($tabErrorMessage["topic"])){echo $tabErrorMessage["topic"];}else{echo "";} ?></p>
                        </div>
                        <div class="input-label2">
                            <div class="input-label">
                                <label for="name">Nom</label>
                                <?php $classValid = (!empty($tabErrorMessage["name"])) ? 'invalidInput' : '' ?>
                                <input type="text" name="name" id="Nom" class="<?=$classValid?>" placeholder="Votre nom" value="<?php if(isset($tabCorrectInput["name"])){echo $tabCorrectInput["name"];}else if(isset($userTab["name"])){echo $userTab["name"];}else{echo "";}?>" required>
                                <p id="errorMessage-contact-name" class="errorMessage"><?php if(isset($tabErrorMessage["name"])){echo $tabErrorMessage["name"];}else{echo "";} ?></p>
                            </div>
                            <div class="input-label">
                                <label for="firstName">Prénom</label>
                                <?php $classValid = (!empty($tabErrorMessage["firstName"])) ? 'invalidInput' : '' ?>
                                <input type="text" name="firstName" id="Prénom" class="<?=$classValid?>" placeholder="Votre prénom" value="<?php if(isset($tabCorrectInput["firstName"])){echo $tabCorrectInput["firstName"];}else if(isset($userTab["firstName"])){echo $userTab["firstName"];}else{echo "";}?>"  required>
                                <p id="errorMessage-contact-firstName" class="errorMessage"><?php if(isset($tabErrorMessage["firstName"])){echo $tabErrorMessage["firstName"];}else{echo "";} ?></p>
                            </div>
                        </div>
                        <div class="input-label">
                            <label for="sexChoice">Sexe</label> 
                            <?php $classValid = (!empty($tabErrorMessage["name"])) ? 'invalidInput' : '' ?>
                            <div id="sexChoice">
                                <input type="radio" class="<?=$classValid?>" id="sexMan" name="sex" value="Man" <?php if(isset($tabCorrectInput["sex"]) && $tabCorrectInput["sex"] === "Man"){echo "checked";}else if(isset($userTab["sex"]) && $userTab["sex"] === "Man"){echo "checked";}else{echo "";} ?>>
                                <label for="sexMan">Homme</label>
                            
                                <input type="radio" class="<?=$classValid?>" id="sexWoman" name="sex" value="Woman" <?php if(isset($tabCorrectInput["sex"]) && $tabCorrectInput["sex"] === "Woman"){echo "checked";}else if(isset($userTab["sex"]) && $userTab["sex"] === "Woman"){echo "checked";}else{echo "";} ?>>
                                <label for="sexWoman">Femme</label>

                                <input type="radio" class="<?=$classValid?>" id="sexOther" name="sex" value="Other" <?php if(isset($tabCorrectInput["sex"]) && $tabCorrectInput["sex"] === "Other"){echo "checked";}else if(isset($userTab["sex"]) && $userTab["sex"] === "Other"){echo "checked";}else{echo "";} ?>>
                                <label for="sexOther">Autre</label>
                            </div>  
                            <p id="errorMessage-contact-sex" class="errorMessage"><?php if(isset($tabErrorMessage["sex"])){echo $tabErrorMessage["sex"];}else{echo "";} ?></p>
                        </div>
                        <div class="input-label2">
                            <div class="input-label">
                                <label for="mail">Adresse mail</label>
                                <?php $classValid = (!empty($tabErrorMessage["name"])) ? 'invalidInput' : '' ?>
                                <input type="Email" name="mail" id="Email" class="<?=$classValid?>" placeholder="Adresse mail" value="<?php if(isset($tabCorrectInput["mail"])){echo $tabCorrectInput["mail"];}else if(isset($userTab["mail"])){echo $userTab["mail"];}else{echo "";}?>"required>
                                <p id="errorMessage-contact-mail" class="errorMessage"><?php if(isset($tabErrorMessage["mail"])){echo $tabErrorMessage["mail"];}else{echo "";} ?></p>
                            </div>
                            <div class="input-label">
                                <label for="contactDate">Date de contact</label>
                                <?php $classValid = (!empty($tabErrorMessage["name"])) ? 'invalidInput' : '' ?>
                                <input type="date" name="contactDate" id="date_de_contact" class="<?=$classValid?>" min="" style="width: 200px" value="<?php if(isset($tabCorrectInput["contactDate"])){echo $tabCorrectInput["contactDate"];}else {date_default_timezone_set('Europe/Paris');echo date("Y-m-d");}?>" required>
                                <p id="errorMessage-contact-contactDate" class="errorMessage"><?php if(isset($tabErrorMessage["contactDate"])){echo $tabErrorMessage["contactDate"];}else{echo "";} ?></p>
                            </div>
                        </div>
                        <div class="input-label">
                            <label for="message">Message</label>
                            <?php $classValid = (!empty($tabErrorMessage["name"])) ? 'invalidInput' : '' ?>
                            <input type="text" name="message" id="message" class="<?=$classValid?>" placeholder="Votre message" style="height: 150px; width: 500px" value="<?php if(isset($tabCorrectInput["message"])){echo $tabCorrectInput["message"];}else{echo "";} ?>" required>
                            <p id="errorMessage-contact-message" class="errorMessage"><?php if(isset($tabErrorMessage["message"])){echo $tabErrorMessage["message"];}else{echo "";} ?></p>
                        </div>
                        <div class="input-label">
                            <input type="submit" class="but_link pointer" name="submitContact" id="Valider" value="Valider l'envoi">
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>
    <?php
        include("./footer.php");
    ?>
    <script src="./js/script_manage_form.js"></script>
</body>
</html>